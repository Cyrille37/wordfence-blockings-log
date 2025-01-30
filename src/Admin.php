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

        add_action( 'admin_notices', [$this, 'wp_admin_notices'] );
    }

    /**
     * https://developer.wordpress.org/reference/hooks/admin_notices/
     */
    public function wp_admin_notices()
    {
        // Check Wordfence is installed and activated
        if ( ! is_plugin_active( 'wordfence/wordfence.php' ) ) {
            ?>
            <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Wordfence blockings log error: Wordfence plugin must be installed and activated.', Plugin::TEXTDOMAIN ); ?></p>
            </div>
            <?php
        }
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
                        'title' => __('Informations', Plugin::TEXTDOMAIN),
                        'include' => __DIR__ . '/admin-info.php',
                    ],
                    'section-two' => [
                        'title' => __('Settings', Plugin::TEXTDOMAIN),
                        'fields' => [
                            [
                                'id' => LogFile::OPTION_ROTATE,
                                'title' => __('Rotate mode', Plugin::TEXTDOMAIN),
                                'type' => 'radio',
                                'choices' => array(
                                    LogFile::ROTATE_NONE => __('No rotation', Plugin::TEXTDOMAIN),
                                    LogFile::ROTATE_DAY => __('Day rotation', Plugin::TEXTDOMAIN),
                                    LogFile::ROTATE_SIZE => __('File size rotation', Plugin::TEXTDOMAIN),
                                ),
                                'value' => LogFile::ROTATE_DEFAULT,
                            ],
                            [
                                'id' => LogFile::OPTION_MAXSIZE,
                                'title' => __('File size for rotation <br/>(in bytes)', Plugin::TEXTDOMAIN),
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
