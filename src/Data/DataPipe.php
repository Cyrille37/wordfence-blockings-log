<?php

namespace WF2CS\Data;

require_once(__DIR__.'/FailedLockException.php');
require_once(__DIR__.'/MaxSizeException.php');

class DataPipe
{
	/**
	 * @var string
	 */
	const DEFAULT_FILENAME = '/dev/shm/wordfence-to-crowdsec.pipe';
	/**
	 * 2000 = 2 milliseconds
	 */
	const DEFAULT_SLEEP_µS = 2000;
	const DEFAULT_ATTEMPS = 24;
	/**
	 * @var int Default max size to 30MB
	 */
	const DEFAULT_MAXSIZE = 1000000 * 30;
	/**
	 * @var \SplFileObject
	 */
	protected $file;
	/**
	 * @var int
	 */
	protected $maxSize;
	/**
	 * @var bool
	 */
	protected $blockingLockRequest;
	/**
	 * $sleep_µs is used when $blockingLockRequest is true
	 * @var int
	 */
	protected $sleep_µs;
	/**
	 * $attempts is used when $blockingLockRequest is true
	 * @var int
	 */
	protected $attempts;
	/**
	 * Not really used, just for Proof of Concept.
	 * @var int
	 */
	public $attempstUsed = 0;

	/**
	 * $sleep_µs & $attempts are only used when $blockingLockRequest is true
	 */
	public function __construct(
		$filename = self::DEFAULT_FILENAME,
		$blockingLockRequest = true,
		$maxSize = self::DEFAULT_MAXSIZE,
		$sleep_µs = self::DEFAULT_SLEEP_µS,
		$attempts = self::DEFAULT_ATTEMPS
	) {
		$this->blockingLockRequest = $blockingLockRequest;
		$this->sleep_µs = $sleep_µs;
		$this->attempts = $attempts;
		$this->maxSize = $maxSize;
		$this->file = new \SplFileObject($filename, 'a+');
	}

	function __destruct()
	{
		// SplFileObject has no method to close the file handle
		$this->close();
		//parent::__destruct();
	}

	/**
	 * Try an exclusive lock.
	 * @throws \Exception if failed to get the lock.
	 * @return bool
	 */
	protected function lock()
	{
		$mode = $this->blockingLockRequest ? LOCK_EX : LOCK_EX | LOCK_NB;
		$attempts = 0;
		while (true) {
			// do an exclusive lock with no blocking request
			if ($this->file->flock($mode)) {
				return true;
			} else {
				if ($attempts >= $this->attempts)
					throw new FailedLockException('Failed to get exclusive access to file after '.$attempts.' attempts.');
				$attempts++;
				$this->attempstUsed += $attempts;
				usleep($this->sleep_µs);
			}
		}
		return false;
	}

	public function close()
	{
		$this->file = null;
	}

	/**
	 * @throws \Exception if failed to get the lock.
	 */
	public function addLines(array $linesOfString)
	{
		if ($this->file->getSize() > $this->maxSize) {
			throw new MaxSizeException('Maximum size has been reached (' . $this->maxSize . ' bytes).');
		}

		if (!$this->lock()) {
			throw new FailedLockException('Failed to get exclusive access to file.');
		}
		foreach ($linesOfString as $line) {
			$this->file->fwrite($line . "\n");
		}
		$this->file->flock(LOCK_UN);   // release the lock
	}

	/**
	 * @return array Array of strings
	 */
	public function &readLinesAndFlush()
	{
		// request a lock
		if (!$this->lock()) {
			throw new FailedLockException('Failed to get exclusive access to file.');
		}
		$this->file->rewind();
		$lines = [];
		$linesCount = 0;
		while (!$this->file->eof()) {
			$lines[] = $this->file->fgets();
			$linesCount++;
		}
		$this->file->ftruncate(0);
		// release the lock
		$this->file->flock(LOCK_UN);
		// last line is empty
		if ($lines[$linesCount - 1] == '')
			//unset($lines[0]);
			array_pop($lines);
		return $lines;
	}
}
