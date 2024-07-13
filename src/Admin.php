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
                        'title' => __('Section One', Plugin::TEXTDOMAIN),
                        'fields' => [
                            'datapipe_file' => [
                                'title' => __('Exchange file', Plugin::TEXTDOMAIN),
                                'type' => 'text',
                                'value' => Listener::LOG_FILE,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $option_page = new \RationalOptionPages($pages);
    }
}
