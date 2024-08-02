<?php

namespace WfBL;

class LogFile
{

    const LOG_FOLDER = 'wp-content/logs';
    const LOG_EXT = '.log';
    const LOG_FILE = 'wordfence_security_event' . self::LOG_EXT;
    const LOG_PERM = 0644 ;

    const OPTION_MAXSIZE = 'logfile_maxsize';
    const MAXSIZE_DEFAULT = 1024 * 1024 * 1;
    const MAXSIZE_MIN = 1024 * 100;

    const OPTION_ROTATE = 'logfile_rotate';
    const ROTATE_NONE = 'none';
    const ROTATE_DAY = 'day';
    const ROTATE_SIZE = 'size';
    const ROTATE_DEFAULT = self::ROTATE_DAY;

    protected $filename;
    protected $plugin;

    public function __construct()
    {
        $this->plugin = Plugin::getInstance();

        $dir = constant('ABSPATH') . self::LOG_FOLDER;
        /*
         mkdir(
            string $directory,
            int $permissions = 0777,
            bool $recursive = false,
            ?resource $context = null
        ): bool
        */
        if (!file_exists($dir))
            mkdir($dir, self::LOG_PERM, true);
        $this->filename = $dir . '/' . self::LOG_FILE;
        $this->rotate();
    }

    public function log($line)
    {
        file_put_contents(
            $this->filename,
            $line . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    protected function rotate()
    {
        if (!file_exists($this->filename))
        {
            touch($this->filename);
            chmod($this->filename, self::LOG_PERM);
            return ;
        }
        $fstat = stat($this->filename);
        $mode = $this->plugin->getOption(self::OPTION_ROTATE, self::ROTATE_DEFAULT);
        switch ($mode) {
            case self::ROTATE_SIZE:
                if ($fstat['size'] >= $this->plugin->getOption(self::OPTION_MAXSIZE, self::MAXSIZE_DEFAULT)) {
                    $back = str_replace(self::LOG_EXT, '-' . date('Ymd_His') . self::LOG_EXT, $this->filename);
                    rename($this->filename, $back);
                }
                break;
            case self::ROTATE_DAY:
                $now = new \DateTime('now');
                $now->setTime(0, 0);
                $mtime = new \DateTime('@' . $fstat['mtime']);
                $mtime->setTime(0, 0);
                if ($now != $mtime) {
                    $back = str_replace(self::LOG_EXT, '-' . date('Y-m-d') . self::LOG_EXT, $this->filename);
                    rename($this->filename, $back);
                }
                break;
        }
    }
}
