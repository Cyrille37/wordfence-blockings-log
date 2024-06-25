<?php

namespace WfBL;

/**
 * Wordfence
 * - https://cyrille.giquello.fr/informatique/securite/wordfence#wp_actions
 *
 * Crowdsec
 * - [Help - custom parser, scenario - I think I’m close](https://discourse.crowdsec.net/t/help-custom-parser-scenario-i-think-im-close/513)
 *
 * @package WfBL
 */
class Listener
{
    const LOG_FOLDER = 'wp-content/logs' ;
    const LOG_FILE = 'wordfence_security_event.log' ;

    /**
     * @return \WfBL\Listener
     */
    public static function getInstance()
    {
        static $_instance;
        if ($_instance == null)
            $_instance = new static();
        return $_instance;
    }

    private function __construct()
    {
        add_action('wordfence_security_event', [$this, 'wordfence_security_event'], 10, 2);
        //add_action('init', [$this, 'wp_init']);
    }

    public function wp_init()
    {
        //do_action('wordfence_security_event', 'loginLockout', ['ip', 'reason', 'duration']);
    }

    /**
     * do_action('wordfence_security_event', 'loginLockout', ['ip', 'reason', 'duration'])
     */
    public function wordfence_security_event($what, $data)
    {
        //Plugin::debug(__METHOD__);

        // [25/Jun/2024:09:34:33 +0200]
        $date = new \DateTimeImmutable();
        $date = $date->format('d/M/Y:H:i:s O');

        $ip = $data['ip'] ?? 'x.x.x.x';
        $duration = $data['duration'] ?? '0';
        $reason = $data['reason'] ?? '?';

        $line = '[' . $date . '] [' . $ip . '] [' . $duration . '] [' . $reason . ']';

        file_put_contents(
            $this->getLogFilename(),
            $line . "\n",
            FILE_APPEND
        );
    }

    protected function getLogFilename()
    {
        /*
         mkdir(
            string $directory,
            int $permissions = 0777,
            bool $recursive = false,
            ?resource $context = null
        ): bool
        */
        //error_log('ABSPATH: ' . constant('ABSPATH'));
        $dir = constant('ABSPATH') . self::LOG_FOLDER;
        if (!file_exists($dir))
            mkdir($dir, 0777, true);
        return $dir . '/' . self::LOG_FILE ;
    }
}
