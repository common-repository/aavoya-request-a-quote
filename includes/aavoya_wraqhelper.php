<?php

if (!defined('ABSPATH')) {
	exit;
}

/*This file For helper functions*/

/**
 * This to create a new post or to update an already existing post.
 * Here we are referring shortcode as post entry.
 * Here you can find more about 'wp_insert_post()' https://developer.wordpress.org/reference/functions/wp_insert_post/
 * @param integer $aavoya_post_id
 * @return  integer post ID
 */
function aavoya_wraqcous($aavoya_post_id = '', $post_status = 'published')
{
	$post_arguments_array = array(
		'ID' => $aavoya_post_id,
		'post_status' => 'publish',
		'post_type' => 'aavoya_wraq'
	);
	return wp_insert_post($post_arguments_array);
}



/**
 * aavoya_apply_global_styles
 * @param  int $button_id 
 * @return int/boolean int on success and boolean false on failure
 */
function aavoya_apply_global_style($button_id = null)
{

	if ($button_id == null) {
		return;
	}
	/**
	 * getting default/global settings from database
	 */
	$global_style = aavoya_get_global_data();



	/**
	 * sanitizing/escaping again before handling 
	 * Its kind of stupid to add this validation here where we are getting data from database and adding it back to database
	 * Since i don't want this plugin validation get stuck at any point for data validation/sanitization/escaping
	 * 
	 * It may create performance issues 
	 */


	$button_meta = array(
		'contact7form'      => intval(aavoya_wraqgoc7f()),
		'buttonbgcolor'		=> ($global_style['globalbuttonbgcolor']) ? $global_style['globalbuttonbgcolor'] : null,
		'buttontextcolor'	=> ($global_style['globalbuttontextcolor']) ? $global_style['globalbuttontextcolor'] : null,
		'borderradiusvalue'	=> ($global_style['globalborderradiusvalue']) ? $global_style['globalborderradiusvalue'] : null,
		'paddingxvalue'		=> ($global_style['globalpaddingxvalue']) ? $global_style['globalpaddingxvalue'] : null,
		'paddingyvalue'		=> ($global_style['globalpaddingyvalue']) ? $global_style['globalpaddingyvalue'] : null,
		'buttonfontsize'	=> ($global_style['globalbuttonfontsize']) ? $global_style['globalbuttonfontsize'] : null,
		'buttontracking'	=> ($global_style['globalbuttontracking']) ? $global_style['globalbuttontracking'] : null,
		'buttontext'		=> ($global_style['globalbuttontext']) ? $global_style['globalbuttontext'] : null,
		'buttoncssclass'	=> ($global_style['globalbuttoncssclass']) ? $global_style['globalbuttoncssclass'] : null

	);

	/**
	 * adding button meta
	 * more about add_post_meta() https://developer.wordpress.org/reference/functions/add_post_meta/
	 */


	return add_post_meta($button_id, 'aavoya_wraq_meta_key', serialize($button_meta));
}



/**
 * Get oldest contact 7 form id
 * @param null
 * @return int $firstKey
 * QA: PASS
 */
function aavoya_wraqgoc7f()
{

	/**
	 * Getting all contact 7 forms
	 */
	$contact7formlist = aavoya_wraqgc7fl();

	/**
	 * Sorting array elements by its key. Key with lowest value(not keys value, treating the key as value) will get listed first.
	 * Objective: To get the oldest contact 7 form id.
	 * more info on ksort: https://www.php.net/manual/en/function.ksort.php or https://www.w3schools.com/php/func_array_ksort.asp
	 * note: ksort works on "call by reference" not "call by value" , So the original array will get modified. Because of that we
	 * need not to store the return value.
	 */
	ksort($contact7formlist, SORT_NUMERIC);

	/**
	 * Now from the above array picking the very first key
	 * Since array_key_first() introduced in Php 7.3.0 it will not php version below 7.3.0
	 * For that purpose only we are using if else in case its below 7.3.0 version then else statement will get executed
	 * and no need to worry about anything .
	 * more about array_key_first() : https://www.php.net/manual/en/function.array-key-first.php
	 */

	$firstKey = '';

	if (function_exists('array_key_first')) {

		$firstKey = array_key_first($contact7formlist);
	} else {

		foreach ($contact7formlist as $key => $contact7form) {
			$firstKey = $key;
			break;
		}
	}



	return intval($firstKey);
}



/**
 * This to get a list of forms of contact 7
 * @return array $formatted_forms
 * QA: PASS
 */
function aavoya_wraqgc7fl()
{

	$argument = array(
		/**
		 * wpcf7_contact_form - This is the post type of contact form 7 .
		 * we may need to change in case contact 7 form change it in future.
		 */
		'post_type' => 'wpcf7_contact_form',
		'posts_per_page' => -1
	);


	/**
	 * $contact_7_forms : contains all contact 7 forms(post of post type wpcf7_contact_form).
	 * Here you can find about 'get_posts()' https://developer.wordpress.org/reference/functions/get_posts/
	 */
	$contact_7_forms = get_posts($argument);


	/**
	 * An empty array to hold contact forms with title and id.
	 */
	$formatted_forms = array();


	foreach ($contact_7_forms as $contact_7_form) {

		$formatted_forms[intval($contact_7_form->ID)] = esc_html($contact_7_form->post_title);
	}

	return $formatted_forms;
}




/**
 * This to get all existing list of shortcodes
 * @return array
 * QA:PASS
 * */
function aavoya_wraqgap()
{


	/**
	 * getting all the contact 7 forms
	 * TODO: add our own form system to have more control in pro version to do some magic
	 */
	$formatted_forms = aavoya_wraqgc7fl();



	/**
	 * below array of arguments is used to get all shortcodes 
	 * from custom post type of "aavoya_wraq"
	 */
	$argument = array(
		'post_type' => 'aavoya_wraq',
		'post_per_page' => -1,
		'numberposts' => -1

	);



	/**
	 * getting all the shortcodes
	 */
	$shortcodes = get_posts($argument);



	/**
	 * This to hold shortcode and form data for frontend rows
	 */
	$container = array();



	/**
	 * preparing the row bsaed on total number of shortcodes 
	 */
	foreach ($shortcodes as $key => $shortcode) {

		$container[$key]['id'] = intval($shortcode->ID);
		$container[$key]['short_code'] = strval('[awraqsci id="' . $shortcode->ID . '"]');
		$container[$key]['forms'] = $formatted_forms;
		$container[$key]['postmeta'] = aavoya_gpm($shortcode->ID);
	}



	/**
	 * returning the data to ajax handler
	 */
	return $container;
}



/**
 * aavoya_gpm
 * It will return all the needed post meta value in a array
 * @param int $id
 * @return false/array
 * QA:PASS
 */
function aavoya_gpm($id = null)
{
	if ($id == null) return false;

	$button_post_meta =  unserialize(get_post_meta($id, 'aavoya_wraq_meta_key', true));

	$button_post_meta_safe =  array(
		'contact7form'      => ($button_post_meta['contact7form']) ? intval($button_post_meta['contact7form']) : null,
		'buttonbgcolor'     => ($button_post_meta['buttonbgcolor']) ? esc_attr($button_post_meta['buttonbgcolor']) : null,
		'buttontextcolor'   => ($button_post_meta['buttontextcolor']) ? esc_attr($button_post_meta['buttontextcolor']) : null,
		'borderradiusvalue' => ($button_post_meta['borderradiusvalue']) ? intval($button_post_meta['borderradiusvalue']) : null,
		'paddingxvalue'     => ($button_post_meta['paddingxvalue']) ? intval($button_post_meta['paddingxvalue']) : null,
		'paddingyvalue'     => ($button_post_meta['paddingyvalue']) ? intval($button_post_meta['paddingyvalue']) : null,
		'buttonfontsize'    => ($button_post_meta['buttonfontsize']) ? intval($button_post_meta['buttonfontsize']) : null,
		'buttontracking'    => ($button_post_meta['buttontracking']) ? intval($button_post_meta['buttontracking']) : null,
		'buttontext'        => ($button_post_meta['buttontext']) ? esc_textarea($button_post_meta['buttontext']) : null,
		'buttoncssclass'    => ($button_post_meta['buttoncssclass']) ? esc_textarea($button_post_meta['buttoncssclass']) : null
	);

	return $button_post_meta_safe;
}



/**
 * aavoya_wraqap
 * This to add post meta 
 * shortcodes button setting gets stored as post meta. As shotcodes as post
 * @param  int $id
 * @param  mixed $postdata
 * @return bool
 * QA:PASS
 */
function aavoya_wraqap($id = null, $postdata = null)
{

	if ($id == null or $postdata == null) {
		return false;
	}

	$postdata_safe = array(
		'contact7form'		=> ($postdata['contact7form']) ? intval($postdata['contact7form']) : null,
		'borderradiusvalue'	=> ($postdata['borderradius']) ? intval($postdata['borderradius']) : null,
		'paddingxvalue'		=> ($postdata['paddingx']) ? intval($postdata['paddingx']) : null,
		'paddingyvalue'		=> ($postdata['paddingy']) ? intval($postdata['paddingy']) : null,
		'buttonbgcolor'		=> ($postdata['buttonbgcolor']) ? sanitize_hex_color($postdata['buttonbgcolor']) : null,
		'buttontextcolor'	=> ($postdata['buttontextcolor']) ? sanitize_hex_color($postdata['buttontextcolor']) : null,
		'buttontext'		=> ($postdata['buttontext']) ? sanitize_text_field($postdata['buttontext']) : null,
		'buttontracking'	=> ($postdata['buttontracking']) ? intval($postdata['buttontracking']) : null,
		'buttonfontsize'	=> ($postdata['buttonfontsize']) ? intval($postdata['buttonfontsize']) : null,
		'buttoncssclass'	=> ($postdata['buttoncssclass']) ? sanitize_html_class($postdata['buttoncssclass']) : null
	);


	$postdata_safe = serialize($postdata_safe);

	update_post_meta($id, "aavoya_wraq_meta_key", $postdata_safe);

	return true;
}


/**
 * aavoya_wraqdp
 * This to delete a button(post) and all of its setting data.
 * @param  mixed $id
 * @return bool
 * QA:PASS
 */
function aavoya_wraqdp($id = null)
{
	if ($id == null) {
		return false;
	}

	delete_post_meta($id, "aavoya_wraq_meta_key");
	wp_delete_post($id, true);
	return true;
}



/**
 * aavoya_wraqwop
 * This to prepare product and shortcode data as ajax reply to be sent to front end
 * @return void
 * QA:PASS
 */
function aavoya_wraqwop()
{

	$argument = array(
		'post_type'     => 'product',
		'post_per_page' => -1,
		'numberposts'   => -1
	);

	/*
	* getting all products form woocommerce
	*/
	$wraq_products =  get_posts($argument);

	/*
	* getting all the buttons
	*/
	$buttons = aavoya_wraqgap();

	/*
	* array to store filtered shortcodes from button posts
	*/
	$button_shortcodes = array();

	/** 
	 * looping through all the buttons
	 * and filtering the shortcodes
	 */
	foreach ($buttons as $key => $button) {
		$button_shortcodes[$key]['short_code'] = strval($button['short_code']);
		$button_shortcodes[$key]['id'] = intval($button['id']);
	}

	/**
	 * array to store filtered product combined with button codes
	 * Preparing the content for javascript to show them properly
	 */
	$container = array();

	/**
	 * looping through all the products and adding the shortcodes to each product
	 * and adding the product to the container array
	 */
	foreach ($wraq_products as $key => $wraq_product) {

		$container[$key]['id']		= intval($wraq_product->ID);
		$container[$key]['title']	= esc_html($wraq_product->post_title);
		$container[$key]['options']	= $button_shortcodes;
		$container[$key]['button']	= unserialize(get_post_meta($wraq_product->ID, 'aavoya_wraqp_meta_key', true));
	}

	/**
	 * sending the data back to ajax handler
	 */
	return $container;
}

/**
 * This function to save button data as product meta 
 * @param int $product_id 
 * @param array $button_data 
 * @return mixed (int|bool) Meta ID if the key didn't exist, true on successful update, false on failure or if the value passed to the 
 * function is the same as the one that is already in the database.
 * QA:PASS
 */
function aavoya_waraqspm($product_id = null, $button_data = null)
{

	if ($product_id == null || $button_data == null) {
		return false;
	}
	$button_data = serialize($button_data);
	return update_post_meta($product_id, 'aavoya_wraqp_meta_key', $button_data);
}


/**
 * aavoya_add_global_settings_data
 * This function to save to global button data for default styling 
 * @param array $global_settings	
 * @return boolean
 * QA:PASS
 */
function aavoya_add_global_settings_data($global_settings = null)
{
	if ($global_settings != null) {

		$global_settings_safe = array(
			'globalbuttonbgcolor'       => ($global_settings['globalBgColor']) ? sanitize_hex_color($global_settings['globalBgColor']) : null,
			'globalbuttontextcolor'     => ($global_settings['globalTextColor']) ? sanitize_hex_color($global_settings['globalTextColor']) : null,
			'globalborderradiusvalue'   => ($global_settings['globalCorner']) ? intval($global_settings['globalCorner']) : null,
			'globalpaddingxvalue'       => ($global_settings['globalPaddingX']) ? intval($global_settings['globalPaddingX']) : null,
			'globalpaddingyvalue'       => ($global_settings['globalPaddingY']) ? intval($global_settings['globalPaddingY']) : null,
			'globalbuttonfontsize'      => ($global_settings['globalFontSize']) ? intval($global_settings['globalFontSize']) : null,
			'globalbuttontracking'      => ($global_settings['globalTracking']) ? intval($global_settings['globalTracking']) : null,
			'globalbuttontext'          => ($global_settings['globalText']) ? sanitize_text_field($global_settings['globalText']) : null,
			'globalbuttoncssclass'      => ($global_settings['globalCssClass']) ? sanitize_html_class($global_settings['globalCssClass']) : null
		);
		return update_option('aavoya_wraq_global_settings', serialize($global_settings_safe));
	}

	return false;
}


/**
 * aavoya_get_global_data
 * This function to provide to global button data for default styling
 * @return array 
 * QA:PASS
 */
function aavoya_get_global_data()
{
	if (get_option('aavoya_wraq_global_settings') != null) {

		$global_style = unserialize(get_option('aavoya_wraq_global_settings'));

		return array(
			'globalbuttonbgcolor'       => ($global_style['globalbuttonbgcolor']) ? esc_attr($global_style['globalbuttonbgcolor']) : null,
			'globalbuttontextcolor'     => ($global_style['globalbuttontextcolor']) ? esc_attr($global_style['globalbuttontextcolor']) : null,
			'globalborderradiusvalue'   => ($global_style['globalborderradiusvalue']) ? intval($global_style['globalborderradiusvalue']) : null,
			'globalpaddingxvalue'       => ($global_style['globalpaddingxvalue']) ? intval($global_style['globalpaddingxvalue']) : null,
			'globalpaddingyvalue'       => ($global_style['globalpaddingyvalue']) ? intval($global_style['globalpaddingyvalue']) : null,
			'globalbuttonfontsize'      => ($global_style['globalbuttonfontsize']) ? intval($global_style['globalbuttonfontsize']) : null,
			'globalbuttontracking'      => ($global_style['globalbuttontracking']) ? intval($global_style['globalbuttontracking']) : null,
			'globalbuttontext'          => ($global_style['globalbuttontext']) ? esc_textarea($global_style['globalbuttontext']) : null,
			'globalbuttoncssclass'      => ($global_style['globalbuttoncssclass']) ? esc_textarea($global_style['globalbuttoncssclass']) : null
		);
	}
}


/**
 * aavoya_global_data_to_inline_style
 *
 * @return void
 */
function aavoya_global_data_to_inline_style()
{
	$global_style = aavoya_get_global_data();

	$global_style_inline = '';

	if ($global_style['globalbuttonbgcolor']) {
		$global_style_inline .= 'background-color:' . $global_style['globalbuttonbgcolor'] . ';';
	}

	if ($global_style['globalbuttontextcolor']) {
		$global_style_inline .= 'color:' . $global_style['globalbuttontextcolor'] . ';';
	}

	if ($global_style['globalborderradiusvalue']) {
		$global_style_inline .= 'border-radius:' . $global_style['globalborderradiusvalue'] . 'px;';
	}

	if ($global_style['globalpaddingxvalue']) {
		$global_style_inline .= 'padding-left:' . $global_style['globalpaddingxvalue'] . 'px;';
		$global_style_inline .= 'padding-right:' . $global_style['globalpaddingxvalue'] . 'px;';
	}

	if ($global_style['globalpaddingyvalue']) {
		$global_style_inline .= 'padding-top:' . $global_style['globalpaddingyvalue'] . 'px;';
		$global_style_inline .= 'padding-bottom:' . $global_style['globalpaddingyvalue'] . 'px;';
	}

	if ($global_style['globalbuttonfontsize']) {
		$global_style_inline .= 'font-size:' . $global_style['globalbuttonfontsize'] . 'px;';
	}

	if ($global_style['globalbuttontracking']) {
		$global_style_inline .= 'letter-spacing:' . $global_style['globalbuttontracking'] . 'px;';
	}



	return $global_style_inline;
}
