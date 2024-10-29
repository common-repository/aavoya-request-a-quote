<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

/**
 * below array of arguments is used to get all shortcodes
 * from custom post type of "aavoya_wraq"
 */
$argument = array(
	'post_type' => 'aavoya_wraq',
	'post_per_page' => -1,


);

/**
 * getting all the buttons(posts)
 */
$shortcodes = get_posts($argument);

/**
 * deleting buttons
 */
foreach ($shortcodes as $shortcode) {
	wp_delete_post($shortcode->ID, true);
}


unregister_post_type('aavoya_wraq');

delete_option('aavoya_wraq_global_settings');
