<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugin Name: Aavoya Request a Quote
 * Plugin URI: https://www.aavoya.co/aavoya-request-a-quote
 * Description: Add Request a quote button on single products or on Product Categories or Product tags.
 * Version: 2022.07
 * Requires PHP: 7.3.0
 * Author: Pijush Gupta
 * Author URI: https://www.linkedin.com/in/pijush-gupta-php/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: aavoya-woocommerce-request-a-quote
 */




require_once(ABSPATH . '/wp-admin/includes/plugin.php');




/**
 * Absolute path of this Plugin
 */
define('aavoyaWraqAbsolute', plugin_dir_path(__FILE__));




/**
 * Relative path of this Plugin
 */
define('aavoyaWraqRelative', plugins_url('', __FILE__));




/**
 *  Constants for contact 7
 */
if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
	define('aavoyaContact7', TRUE);
} else {
	define('aavoyaContact7', false);
}




/**
 * Constant for WooCom
 */
if (is_plugin_active('woocommerce/woocommerce.php')) {
	define('aavoyaWooCom', true);
} else {
	define('aavoyaWooCom', false);
}





/**
 * On Plugin Activation
 * aavoyaOnActivation
 * Code in this function will get executed on Plugin Activation.
 * @return void
 */
function aavoyaOnActivation()
{
}
register_activation_hook(__FILE__, 'aavoyaOnActivation');







/**
 * On Plugin Deactivation
 * aavoyaOnDeactivation
 * Code in this function will get executed on Plugin Deactivation.
 * @return void
 */
function aavoyaOnDeactivation()
{
}
register_deactivation_hook(__FILE__, 'aavoyaOnDeactivation');







/**
 * On Plugin Uninstall
 * aavoyaOnUninstall
 * Code in this function will get executed on Plugin Uninstall.
 * @return void
 */
function aavoyaOnUninstall()
{
}
register_uninstall_hook(__FILE__, 'aavoyaOnUninstall');








/**
 * After Plugins Loaded
 * aavoyaOnPluginsLoad
 * Code in this function will get executed after Plugins Loading is complete.
 * @return void
 */
function aavoyaOnPluginsLoad()
{

	if (aavoyaContact7 != TRUE) {

		deactivate_plugins('aavoya-woocommerce-request-a-quote/aavoya-woocommerce-request-a-quote.php');
		add_action('admin_notices', 'aavoya_admin_notice');
	} else {

		require_once aavoyaWraqAbsolute . 'includes/index.php';
		require_once aavoyaWraqAbsolute . 'public/index.php';
	}
}
add_action('plugins_loaded', 'aavoyaOnPluginsLoad');







/**
 * aavoya_admin_notice
 *
 * @return void
 */
function aavoya_admin_notice()
{
	echo '<div class="notice notice-error is-dismissible"><p>' . __('Plugin deactivated. Please activate contact form 7 plugin!', 'aavoya-woocommerce-request-a-quote') . '</p></div>';
}
