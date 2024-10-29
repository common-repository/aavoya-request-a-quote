<?php
if (!defined('ABSPATH')) {
	exit;
}


if (class_exists("base")) :

	class aavoya_wraq_fn extends base
	{

		public function __construct()
		{

			/**
			 * hooking js and css method 
			 */
			add_action('wp_enqueue_scripts', array($this, 'aavoya_addcssjsfrontend'));



			/**
			 * adding shortcode for to call button and form for frontend 
			 */
			add_shortcode('awraqsci', array($this, 'aavoya_awraqfi'));



			/**
			 * hooking woocommerce initialization of button creation process  
			 */
			add_action('template_redirect', array($this, 'aavoya_awraqwi'));
		}



		/**
		 * addcssjsfrontend
		 * This method to add frontend css and js 
		 * @return void
		 */
		public function aavoya_addcssjsfrontend()
		{
			wp_enqueue_style('tailwind', aavoyaWraqRelative . '/public/assets/dist/app.css', array(), false, 'all');
			wp_enqueue_script('javascripfunction', aavoyaWraqRelative . '/public/assets/dist/app.js', array('jquery'), false, true);
		}


		/**
		 * awraqfi
		 * Creates the button and form
		 * @param  mixed $attr
		 * @return mixed string/html
		 */
		public function aavoya_awraqfi($attr = null)
		{
			/**
			 * Terminate the program if no post id provided with shortcode
			 */
			if ($attr == null) {
				return "No id Provided";
			}



			/**
			 * Creating object of the class "aavoya_wraqf" as $wraq
			 */
			$wraq = $this->load('aavoya_wraqf', __DIR__);



			/**
			 * Enable and Disable switch verification
			 * Checking if the buttons are enabled or not. This switch located at top-right of button tab
			 */
			if (rest_sanitize_boolean(get_option('wraqwp')) != true) {
				return "Enable it From Plugins Interface";
			}



			$buttonId = intval($attr['id']);
			$buttonText = __('Button', 'aavoya-woocommerce-request-a-quote');
			$cssClass = '';
			$randomValueForJs = rand();



			/**
			 * Getting post meta un-serialized
			 */
			$unserializeButtonMeta = $wraq->aavoya_araqgpm($buttonId);



			/*
			 * Terminate the Program if no post meta found
			 */
			if ($unserializeButtonMeta == false) {
				return;
			}




			if ($unserializeButtonMeta['buttontext']) {

				$buttonText = __($unserializeButtonMeta['buttontext'], 'aavoya-woocommerce-request-a-quote');
			}


			if ($unserializeButtonMeta['buttoncssclass']) {

				$cssClass = $unserializeButtonMeta['buttoncssclass'];
			}


			/**
			 * id of the contact form 7
			 */
			$contact7form = $unserializeButtonMeta['contact7form'];

			//inline css to added to the button 
			$inlineCss = $wraq->aavoya_araqcatc($unserializeButtonMeta);


			return $wraq->aavoya_araqch($inlineCss, $cssClass, $randomValueForJs, $buttonText, $contact7form);
		}

		/**
		 * aavoya_awraqwi
		 * This to initiate button short code on Product IF a product having button id and button status active
		 * @return void
		 */
		public function aavoya_awraqwi()
		{
			if (aavoyaWooCom == TRUE && is_product()) {

				$data = $this->aavoya_wraqgpbd();


				if ($data['buttonid'] && $data['buttonstatus'] == true && get_option('wraqwo') == TRUE && get_option('wraqwp') == TRUE) {

					/**
					 * Removing "Add to cart" button from product page
					 */
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

					/**
					 * Executing shortcode on "woocommerce_product_meta_start" action.
					 */
					add_action('woocommerce_product_meta_start', function () {

						echo do_shortcode('[awraqsci id="' . $this->aavoya_wraqgpbd()['buttonid'] . '"]');
					}, 30);
				}
			}
		}


		/**
		 * aavoya_wraqgpbd
		 * provide post meta of Products of button information 
		 * @return array
		 */
		function aavoya_wraqgpbd()
		{
			if (aavoyaWooCom == TRUE && is_product()) {


				$data = unserialize(get_post_meta(get_the_ID(), 'aavoya_wraqp_meta_key', true));

				/**
				 * sanitization
				 * */
				$data['buttonid'] =  intval($data['buttonid']);
				$data['buttonstatus'] =  rest_sanitize_boolean($data['buttonstatus']);

				return $data;
			}
		}
	} /*class ends here*/

	/**
	 * creating object of aavoya_wraq_fn
	 */
	$aavoya_wraq_fn = new aavoya_wraq_fn();
endif; /* if condition ends here */
