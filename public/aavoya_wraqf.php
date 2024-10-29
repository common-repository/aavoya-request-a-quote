<?php
if (!defined('ABSPATH')) {
	exit;
}
class aavoya_wraqf
{

	public function __construct()
	{
	}


	/**
	 * aavoya_araqgpm
	 * Provides unserialize post meta of custom post type "aavoya_wraq"
	 * @param int $post_id/buttonid
	 * @return array $aavoya_button_meta
	 */
	public function aavoya_araqgpm($post_id = null)
	{

		if ($post_id == null) {
			return false;
		}

		$args = array('post_type' => 'aavoya_wraq', 'p' => intval($post_id));
		$button = get_posts($args);

		if (!empty($button)) {

			$aavoya_button_meta = unserialize(get_post_meta($post_id, 'aavoya_wraq_meta_key', true));

			/**
			 * Sanitizing before sending button meta back
			 */
			$aavoya_button_meta['contact7form']			= intval($aavoya_button_meta['contact7form']);
			$aavoya_button_meta['borderradiusvalue']	= intval($aavoya_button_meta['borderradiusvalue']);
			$aavoya_button_meta['paddingxvalue']		= intval($aavoya_button_meta['paddingxvalue']);
			$aavoya_button_meta['paddingyvalue']		= intval($aavoya_button_meta['paddingyvalue']);
			$aavoya_button_meta['buttonbgcolor']		= sanitize_hex_color($aavoya_button_meta['buttonbgcolor']);
			$aavoya_button_meta['buttontextcolor']		= sanitize_hex_color($aavoya_button_meta['buttontextcolor']);
			$aavoya_button_meta['buttontext']			= sanitize_text_field($aavoya_button_meta['buttontext']);
			$aavoya_button_meta['buttontracking']		= intval($aavoya_button_meta['buttontracking']);
			$aavoya_button_meta['buttonfontsize']		= intval($aavoya_button_meta['buttonfontsize']);
			$aavoya_button_meta['buttoncssclass']		= sanitize_html_class($aavoya_button_meta['buttoncssclass']);

			return $aavoya_button_meta;
		}

		return false;
	}

	/**
	 * aavoya_araqcatc
	 * This to create css from the provided array
	 * @param  array $args
	 * @return string $cssAsString
	 */
	public function aavoya_araqcatc($args = null)
	{
		if ($args == null) {
			return false;
		}

		if (!empty($args) and is_array($args)) {

			$cssAsString = '';

			foreach ($args as $property => $value) {
				switch ($property) {

					case 'borderradiusvalue':
						$cssAsString .= 'border-radius:' . intval($value) . 'px;';
						break;
					case 'paddingxvalue':
						$cssAsString .= 'padding-right:' . intval($value) . 'px;';
						$cssAsString .= 'padding-left:' . intval($value) . 'px;';
						break;
					case 'paddingyvalue':
						$cssAsString .= 'padding-top:' . intval($value) . 'px;';
						$cssAsString .= 'padding-bottom:' . intval($value) . 'px;';
						break;
					case 'buttonbgcolor':
						$cssAsString .= 'background-color:' . sanitize_hex_color($value) . ';';
						break;
					case 'buttontextcolor':
						$cssAsString .= 'color:' . sanitize_hex_color($value) . ';';
						break;
					case 'buttontext':
						break;
					case 'buttontracking':
						$cssAsString .= 'letter-spacing:' . intval($value) . 'px;';
						break;
					case 'buttonfontsize':
						$cssAsString .= 'font-size:' . intval($value) . 'px;';
						break;
					case 'buttoncssclass':
						break;
					default:
						break;
				}
			}
			return $cssAsString;
		}

		return false;
	}

	/**
	 * aavoya_araqch
	 * This to create required html of button and popup form
	 * @param string $inlineCss
	 * @param string $cssClass
	 * @param string $randomValueForJs
	 * @param string $buttonText
	 * @param int $contact7form
	 * @return string $html
	 */
	public function aavoya_araqch($inlineCss = null, $cssClass = null, $randomValueForJs = null, $buttonText = null, $contact7form = null)
	{

		if ($inlineCss == null  or $randomValueForJs == null or $buttonText == null or $contact7form == null) {
			return false;
		}

		$html = '<p><button style="' . esc_html($inlineCss) . '" class="ainipopup ' . esc_attr($cssClass) . '" popuptoopen="araq' . esc_attr($randomValueForJs) . '">' . esc_html($buttonText) . '</button></p>';
		$html .= '<div class="modal contact-7-popup  hidden" id="araq' . esc_attr($randomValueForJs) . '"> <div class="relative bg-white border border-gray-100  m-4 rounded shadow "> <svg class="w-8 h-8 text-gray-500  bg-white rounded-full absolute right-0 m-2 aavoyaclose cursor-pointer shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path> </svg> <div class="modal-body  modal-body overflow-hidden rounded "> <div class="p-4"> ' . do_shortcode('[contact-form-7 id="' . $contact7form . '"]') . '<div class="flex justify-between items-center mt-1"> </div> </div> </div> </div> </div>';

		return $html;
	}
}
