<?php
/*
 * Wordfence to Crowdsec
 * 
 * @wordpress-plugin
 * Plugin Name:       Wordfence blockings log
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Wordfence IP blocking log
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Cyrille37
 * Author URI:        https://framagit.org/Cyrille37
 * License:           MIT
 * License URI:       https://opensource.org/license/MIT
 * Update URI:        
 * Text Domain:       wfbl
 * Domain Path:       /languages
 * Requires Plugins:  
 * 
 * Thanks & Credits to
 * - https://developer.wordpress.org for Wordpress developer documentation
 * - https://github.com/jeremyHixon/RationalOptionPages for Settings & Options
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once ( __DIR__.'/src/Plugin.php');

$wfBLPlugin = \WfBL\Plugin::getInstance() ;

/**
 * Activate the plugin.
 */
function pluginprefix_activate() { 
}
register_activation_hook( __FILE__, 'pluginprefix_activate' );
/**
 * Deactivation hook.
 */
function pluginprefix_deactivate() {
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivate' );

