<?php
/*
Plugin Name: JM Responsive Video Embeds
Plugin URI:  https://gist.github.com/TweetPressFr/5f310eaf962aa921744b
Description: Simple responsive video embeds (YouTube, Dailymotion, etc), no options
Version:     1.2
Author:      TweetPressFr
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'JM_RWD_EMBED_PATH', plugin_dir_path( __FILE__ ) );
define( 'JM_RWD_EMBED_URL', plugin_dir_url( __FILE__ ) );
define( 'JM_RWD_EMBED_VERSION', '1.2' );

/**
 * Delete cache oembed
 * on activation
 * WordPress will refetch provider
 * and we'll get our new markup
 */
function _jm_rwd_video_oembed(){
	global $wpdb;
	$wpdb->query(
		"
		DELETE
		FROM {$wpdb->postmeta}
		WHERE meta_key LIKE '_oembed_%'
		"
	);
}

/**
 * regenerate markup on activation (insert our wrapper)
 */
register_activation_hook( __FILE__, '_jm_rwd_video_oembed' );

/**
 * clean our custom markup on deactivation
 */
register_deactivation_hook( __FILE__, '_jm_rwd_video_oembed' );

/**
 * Init class
 */
function _jm_rwd_plugins_load(){
	if ( ! is_admin() ) {
		require_once ( JM_RWD_EMBED_PATH . 'classes/oembed.php' );
		new JM_RWD_Video_Embeds;
	}
}
add_action( 'plugins_loaded', '_jm_rwd_plugins_load' );