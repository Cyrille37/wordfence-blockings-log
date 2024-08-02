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
    protected $log;

    public function __construct()
    {
        add_action('wordfence_security_event', [$this, 'wordfence_security_event'], 10, 2);
        //add_action('init', [$this, 'wp_init']);
    }

    public function wp_init()
    {
        do_action('wordfence_security_event', 'loginLockout', ['ip', 'reason', 'duration']);
    }

    /**
     * 
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

        $line = '[' . $date . '] [' . $ip . '] [' . $duration . '] [' . $what . '] [' . $reason . ']';

        $this->getLog()->log($line);
    }

    protected function getLog()
    {
        if ($this->log)
            return $this->log;
        require_once(__DIR__ . '/LogFile.php');
        $this->log = new LogFile();
        return $this->log;
    }
}
