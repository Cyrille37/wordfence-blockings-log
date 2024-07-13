<?php

namespace WfBL;

class Admin
{
    /**
     * @return \WfBL\WfBLAdmin
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
        require_once(__DIR__ . '/../lib/RationalOptionPages.php');
        $this->options_page();
    }

    /**
     * Documentation: https://github.com/jeremyHixon/RationalOptionPages
     */
    public function options_page()
    {
        $pages = [
            Plugin::PLUGIN_PREFIX => [
                'parent_slug' => 'tools.php',
                //'position' => 25,
                'page_title' => __('Wordfence blockings log', Plugin::TEXTDOMAIN),
                'sections' => [
                    'section-one' => [
                        'title' => __('Réglages', Plugin::TEXTDOMAIN),
                        'fields' => [
                            Plugin::OPTION_LOGFILE_MAXSIZE => [
                                'title' => __('Max file size (bytes)', Plugin::TEXTDOMAIN),
                                'type' => 'number',
                                'value' => Listener::DEFAULT_MAXSIZE,
                                'attributes' => ['min'=> 1024 * 100],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $option_page = new \RationalOptionPages($pages);
    }
}
