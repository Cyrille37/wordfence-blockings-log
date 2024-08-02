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
        require_once(__DIR__ . '/LogFile.php');
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
                            [
                                'id' => LogFile::OPTION_ROTATE,
                                'title' => __('Rotate mode', Plugin::TEXTDOMAIN),
                                'type' => 'radio',
                                'choices' => array(
                                    LogFile::ROTATE_NONE => __('No rotation', Plugin::TEXTDOMAIN),
                                    LogFile::ROTATE_DAY => __('Day rotation', Plugin::TEXTDOMAIN),
                                    LogFile::ROTATE_SIZE => __('Size rotation', Plugin::TEXTDOMAIN),
                                ),
                                'value' => LogFile::ROTATE_DEFAULT,
                            ],
                            [
                                'id' => LogFile::OPTION_MAXSIZE,
                                'title' => __('Max file size (bytes)', Plugin::TEXTDOMAIN),
                                'type' => 'number',
                                'value' => LogFile::MAXSIZE_DEFAULT,
                                'attributes' => ['min' => LogFile::MAXSIZE_MIN],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $option_page = new \RationalOptionPages($pages);
    }
}
