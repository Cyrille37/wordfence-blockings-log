<?php

namespace WfBL;

class Plugin
{
    const PLUGIN_PREFIX = 'wf2bl';
    const TEXTDOMAIN = self::PLUGIN_PREFIX;

    protected $plugin_dir;
    protected $plugin_dir_url;
    protected $asset_url;
    protected static $debug = false;

    /**
     * @return \WfBL\Plugin
     */
    public static function getInstance()
    {
        static $_instance;
        if ($_instance == null)
            $_instance = new static();
        return $_instance;
    }

    protected $options ;

    private function __construct()
    {
        if ( (defined('WP_DEBUG') && constant('WP_DEBUG')) || (defined('WF2BL_DEBUG') && constant('WF2BL_DEBUG')) )
            self::$debug = true;

        $this->plugin_dir = plugin_dir_path(__FILE__);
        $this->plugin_dir_url = plugin_dir_url(__FILE__);
        $this->asset_url = $this->plugin_dir_url . 'assets/';

        require_once ( __DIR__.'/Listener.php');
        new Listener();

        if( is_blog_admin() )
        {
            require_once ( __DIR__.'/Admin.php');
            Admin::getInstance();
        }
    }

    public function getOption( $key, $default=null )
    {
        if( ! $this->options )
            $this->options = get_option(Plugin::PLUGIN_PREFIX);
        if( ! isset($this->options[$key]) )
            return $default ;
        return $this->options[$key] ;
    }

    public static function debug( ...$items )
    {
        if( ! self::$debug )
            return ;

        $msg = '' ;
        foreach( $items as $item )
        {
            switch ( gettype($item))
            {
                case 'boolean' :
                    $msg.= ($item ? 'true':'false');
                    break;
                case 'NULL' :
                    $msg.= 'null';
                    break;
                case 'integer' :
                case 'double' :
                case 'float' :
                case 'string' :
                    $msg.= $item ;
                    break;
                default:
                    $msg .= var_export($item,true) ;
            }
            $msg.=' ';
        }
        error_log( $msg );
    }

}
