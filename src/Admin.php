<?php

namespace WfBL;

require_once(__DIR__.'/Data/DataPipe.php');

use WfBL\Data\DataPipe;

class WfBLAdmin
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
            WfBLPlugin::PLUGIN_PREFIX => [
                'page_title' => __('Wordfence to Crowdsec', WF2CSPlugin::TEXTDOMAIN),
                'sections' => [
                    'section-one' => [
                        'title' => __('Section One', WF2CSPlugin::TEXTDOMAIN),
                        'fields' => [
                            'datapipe_file' => [
                                'title' => __('Exchange file', WF2CSPlugin::TEXTDOMAIN),
                                'type' => 'text',
                                'value' => DataPipe::DEFAULT_FILENAME,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $option_page = new \RationalOptionPages($pages);
    }
}
