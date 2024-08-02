<?php

namespace WfBL;

class LogFile {

    const LOG_FOLDER = 'wp-content/logs';
    const LOG_EXT = '.log';
    const LOG_FILE = 'wordfence_security_event' . self::LOG_EXT;

    const OPTION_MAXSIZE = 'logfile_maxsize' ;

    const DEFAULT_MAXSIZE = 1024 * 1024 * 1;

    protected $filename ;
    protected $plugin ;

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
            mkdir($dir, 0777, true);
        $this->filename = $dir . '/' . self::LOG_FILE;
        $this->rotate();
    }

    public function log( $line )
    {
        file_put_contents(
            $this->filename,
            $line . "\n",
            FILE_APPEND
        );
    }

    protected function rotate()
    {
        if( ! file_exists($this->filename) )
            return ;
        $fstat = stat($this->filename);
        if ($fstat['size'] >= $this->plugin->getOption(self::OPTION_MAXSIZE, self::DEFAULT_MAXSIZE)) {
            $back = str_replace( self::LOG_EXT, date('Ymd_His').self::LOG_EXT, $this->filename);
            rename($this->filename, $back);
        }
    }

}
