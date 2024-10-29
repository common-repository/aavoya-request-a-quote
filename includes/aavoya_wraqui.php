<?php

if (!defined('ABSPATH')) {
	exit;
}

class aavoya_wraqui extends base
{



	/**
	 * __construct
	 * Simple Constructor to init methods on object creation
	 * Anything within this method will invoke automatically on object creation.
	 * Here you can find more information - w3:https://www.w3schools.com/php/php_oop_constructor.asp or php.net : https://www.php.net/manual/en/language.oop5.decon.php
	 * @return void
	 */
	public function __construct()
	{

		/**
		 * enqueuing style and js on admin by method 'aavoya_woocommerce_request_a_quote_add_css_js' on action 'admin_enqueue_scripts'
		 * since we are using object oriented approach , that is why we are using array instead of simple callback function.
		 * In that array we are referring the pseudo-variable $this and then the method name. more about $this : https://https://www.php.net/manual/en/language.oop5.basic.php
		 */
		add_action('admin_enqueue_scripts', array($this, 'aavoya_woocommerce_request_a_quote_add_css_js'));

		/**
		 * Adding admin menu page(s)
		 * since we are using object oriented approach , that is why we are using array instead of simple callback function.
		 * In that array we are referring the pseudo-variable $this and then the method name. more about $this : https://https://www.php.net/manual/en/language.oop5.basic.php
		 */
		add_action('admin_menu', array($this, 'aavoya_woocommerce_request_a_quote_admin_menu'));
	}



	/**
	 * aavoya_woocommerce_request_a_quote_add_css_js
	 * Adding Assets like javascript and css
	 * @param  string $hook
	 * @return void
	 */
	public function aavoya_woocommerce_request_a_quote_add_css_js($hook)
	{
		/**
		 * The '$hook' variable contain page slug of the plugin.
		 * I'm checking if the page slug matches then only include the javascript and css file else EXIT the method.
		 * The reason to this to avoid any conflict with wordpress's css and js and reduce unnecessary inclusion of the plugin
		 */
		if ($hook != 'toplevel_page_aavoya_woocommerce_request_a_quote_setting') {
			return;
		}

		/**
		 * Css file to style admin page of the plugin
		 * i'm using tailwindcss with NPM , Purge css and laravel-mix(webpack) to generate the css
		 */
		wp_enqueue_style('wordpress-form-css', aavoyaWraqRelative . '/admin/dist/app.css', '', '1', 'all');

		/**
		 * Using jQuery since jQuery is already included in the wordpress core,
		 * in the next(if it gets approved) version will use React to do the development works little easy tightly integrated
		 * i'm using laravel-mix to combine multiple js file and create a single(app.js) js file.
		 * here you can find more information about the method 'wp_enqueue_scripthttps' : https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		 */
		wp_enqueue_script('wordpress-form-js', aavoyaWraqRelative . '/admin/dist/app.js', array('jquery'), '1', true);
	}



	/**
	 * aavoya_woocommerce_request_a_quote_admin_menu
	 * Creating the admin menu
	 */
	public function aavoya_woocommerce_request_a_quote_admin_menu()
	{
		/**
		 * Checking if the current user is having proper privilege(admin or not)
		 * the wordpress method 'current_user_can' doing that for us
		 * here you can find more information about the method : https://developer.wordpress.org/reference/functions/current_user_can/
		 */
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'aavoya-woocommerce-request-a-quote'));
		}

		/**
		 * This method actually adding admin menu page
		 * add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
		 * The 5th argument - callback function - since we are using object oriented approach , that is why we are using array instead of simple callback function.
		 * In that array we are referring the pseudo-variable $this and then the method name. more about $this : https://https://www.php.net/manual/en/language.oop5.basic.php
		 * here you can find more information about the function: https://https://developer.wordpress.org/reference/functions/add_menu_page/
		 */
		add_menu_page(
			__('Aavoya Request a Quote', 'aavoya-woocommerce-request-a-quote'),
			__('Aavoya RAQ', 'aavoya-woocommerce-request-a-quote'),
			'manage_options',
			'aavoya_woocommerce_request_a_quote_setting',
			array($this, 'aavoya_woocommerce_request_a_quote_show_default_page'),
			'dashicons-clipboard',
			20
		);
	}




	/**
	 * aavoya_woocommerce_request_a_quote_show_default_page
	 * Adding items to the default backend page
	 * @return void
	 */
	public function aavoya_woocommerce_request_a_quote_show_default_page()
	{ ?>

		<input type="hidden" name="awraq_nonce" id="awraqnonce" value="<?php echo wp_create_nonce("awraq_nonce"); ?>">
		<input type="hidden" name="awraq_woocom" id="awraqwoocom" value="<?php echo esc_attr(aavoyaWooCom); ?>">
		<header class="header">
			<div class="header-item">
				<span class="title"><strong class="font-bold">Aavoya</strong> Request a Quote</span>
			</div>

		</header>

		<!-- this to show warning message -->
		<div class="modal z-40 bottom-0 right-0 fixed  overflow-y-auto w-96 hidden deletewarning" postid="">
			<div class="bg-white border border-gray-100  m-4 rounded shadow ">
				<svg class="w-8 h-8 text-red-600 float-right -mr-3 -mt-3 bg-white rounded-full close cursor-pointer shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
				</svg>
				<div class="modal-body  modal-body overflow-hidden rounded ">
					<div class="border-l-8 border-red-600 p-4">

						<div class="flex justify-between items-center mt-1">
							<span class="block text-xs tracking-wide font-medium"><?php _e('Are you sure ?', 'aavoya-woocommerce-request-a-quote'); ?></span>
							<button class="del-yes rounded px-8 py-2  bg-red-500 text-white"><?php _e('Yes', 'aavoya-woocommerce-request-a-quote'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- ends -->

		<!-- ad area. . Its for future. In case this this plugin gets popular i might sale some really cheap pro version for living cost 🤣  and become full-time plugin dev instead being a $5 dollar on fiverr.com -->
		<div class="msg-area flex items-center mx-auto max-w-screen-lg py-4 px-4 bg-white shadow mt-4 hidden ">
			<div class="p-3 rounded-full bg-blue-200 mr-4">
				<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-5 w-5 text-blue-900 " viewBox="0 0 408.576 408.576">
					<path d="M204.288 0C91.648 0 0 91.648 0 204.288s91.648 204.288 204.288 204.288 204.288-91.648 204.288-204.288S316.928 0 204.288 0zm114.176 150.528l-130.56 129.536c-7.68 7.68-19.968 8.192-28.16.512L90.624 217.6c-8.192-7.68-8.704-20.48-1.536-28.672 7.68-8.192 20.48-8.704 28.672-1.024l54.784 50.176L289.28 121.344c8.192-8.192 20.992-8.192 29.184 0s8.192 20.992 0 29.184z" />
				</svg>
			</div>
			<div class="text-xs tracking-wide font-medium ">My Sisters/Brothers from another mother please <a href="" class="hyperlink">wear a mask</a> and Help Fight COVID-19</div>

			<div class="ml-auto">
				<svg xmlns="http://www.w3.org/2000/svg" class="no-msg fill-current h-5 w-5 text-blue-900 cursor-pointer" viewBox="0 0 20 20">
					<path d="M2.93 17.07A10 10 0 1117.07 2.93 10 10 0 012.93 17.07zM11.4 10l2.83-2.83-1.41-1.41L10 8.59 7.17 5.76 5.76 7.17 8.59 10l-2.83 2.83 1.41 1.41L10 11.41l2.83 2.83 1.41-1.41L11.41 10z" />
				</svg>
			</div>
		</div>
		<!-- ad area ends here -->

		<!-- never planned this div, but end up creating one to hold the status messages holder.  -->
		<div class="message-area"></div>
		<!-- message area ends here -->

		<!-- main container area -->
		<div class="container body flex flex-row">
			<div class="top-bar w-1/5 pr-2">
				<ul>
					<!-- left hand side vertical navigation bar -->
					<li data-target="aavoya-wraq-wordpress" class="aavoya-wraq-tab active ">
						<svg xmlns="http://www.w3.org/2000/svg" class="svg-class" viewBox="0 0 31.125 31.125">
							<path d="M.001 15.563c0 6.159 3.579 11.483 8.771 14.007L1.348 9.23a15.533 15.533 0 00-1.347 6.333zm26.068-.787c0-1.923-.69-3.255-1.283-4.291-.787-1.284-1.528-2.366-1.528-3.649 0-1.429 1.086-2.762 2.613-2.762.068 0 .134.008.203.011A15.513 15.513 0 0015.565 0C10.127 0 5.345 2.79 2.562 7.016c.365.011 3.153-.094 3.153-.094l5.981 18.2 3.406-10.213-2.776-8.073h5.146l5.859 18.158 1.555-5.188c.787-2.022 1.183-3.697 1.183-5.03zm-10.233 2.149l-4.67 13.566a15.59 15.59 0 009.565-.247 1.51 1.51 0 01-.113-.215l-4.782-13.104zm13.382-8.829c.068.496.105 1.027.105 1.602 0 1.578-.297 3.353-1.186 5.572l-4.752 13.743c4.627-2.698 7.737-7.709 7.737-13.45a15.448 15.448 0 00-1.904-7.467z" />
						</svg>
						<?php _e('Buttons', 'aavoya-woocommerce-request-a-quote');  ?>
					</li>
					<li data-target="aavoya-wraq-woocommerce" class="aavoya-wraq-tab ">
						<svg xmlns="http://www.w3.org/2000/svg" class="svg-class" viewBox="0 0 456.029 456.029">
							<path d="M345.6 338.862c-29.184 0-53.248 23.552-53.248 53.248 0 29.184 23.552 53.248 53.248 53.248 29.184 0 53.248-23.552 53.248-53.248-.512-29.184-24.064-53.248-53.248-53.248zM439.296 84.91c-1.024 0-2.56-.512-4.096-.512H112.64l-5.12-34.304C104.448 27.566 84.992 10.67 61.952 10.67H20.48C9.216 10.67 0 19.886 0 31.15c0 11.264 9.216 20.48 20.48 20.48h41.472c2.56 0 4.608 2.048 5.12 4.608l31.744 216.064c4.096 27.136 27.648 47.616 55.296 47.616h212.992c26.624 0 49.664-18.944 55.296-45.056l33.28-166.4c2.048-10.752-5.12-21.504-16.384-23.552zM215.04 389.55c-1.024-28.16-24.576-50.688-52.736-50.688-29.696 1.536-52.224 26.112-51.2 55.296 1.024 28.16 24.064 50.688 52.224 50.688h1.024c29.184-1.536 52.224-26.112 50.688-55.296z" />
						</svg>
						<?php _e('Woocommerce', 'aavoya-woocommerce-request-a-quote') ?>
					</li>
					<li data-target="aavoya-setting" class="aavoya-wraq-tab">
						<svg xmlns="http://www.w3.org/2000/svg" class="svg-class" viewBox="0 0 20 20" fill="currentColor">
							<path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
						</svg>
						<?php _e('Settings', 'aavoya-woocommerce-request-a-quote'); ?>
					</li>
					<li data-target="aavoya-wraq-help" class="aavoya-wraq-tab">
						<svg xmlns="http://www.w3.org/2000/svg" class="svg-class" viewBox="0 0 20 20">
							<path d="M17.16 6.42a8.03 8.03 0 0 0-3.58-3.58l-1.34 2.69a5.02 5.02 0 0 1 2.23 2.23l2.69-1.34zm0 7.16l-2.69-1.34a5.02 5.02 0 0 1-2.23 2.23l1.34 2.69a8.03 8.03 0 0 0 3.58-3.58zM6.42 2.84a8.03 8.03 0 0 0-3.58 3.58l2.69 1.34a5.02 5.02 0 0 1 2.23-2.23L6.42 2.84zM2.84 13.58a8.03 8.03 0 0 0 3.58 3.58l1.34-2.69a5.02 5.02 0 0 1-2.23-2.23l-2.69 1.34zM10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-7a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
						</svg>
						<?php _e('Help', 'aavoya-woocommerce-request-a-quote'); ?>
					</li>

					<!-- left hand side vertical navigation bar ends here -->
				</ul>
			</div>
			<div class=" w-4/5 aavoya-wraq-tab-body">



				<div id="aavoya-wraq-wordpress" class="tab-body-area border w-full  bg-white">
					<?php
					if (get_option('wraqwp') == true) {
						$wraqwp_checked = 'checked';
						$hidden = '';
					} else {
						$wraqwp_checked = '';
						$hidden = 'hidden';
					}
					?>
					<div class="wordpress-form-area">
						<div class="mt-2 border-b border-gray-100 py-3 px-6 flex items-center justify-between">
							<label class="text-xs tracking-wide font-medium" for="enable-wordpress-id"><span><?php _e('Enable Buttons.', 'aavoya-woocommerce-request-a-quote'); ?></label>
							<input class="enable-wordpress-class" id="enable-wordpress-id" name="enable-wordpress" type="checkbox" <?php echo esc_attr($wraqwp_checked); ?>>
						</div>
						<div class="py-2 px-2 wp-form-map-table">
							<div id="wordpress-default-setting-area" class="<?php echo esc_attr($hidden); ?> relative ">

								<div class="wp-appender">
								</div>

								<button class="wpadder rounded flex ml-auto mr-0 px-8 py-2 mt-2 bg-blue-900 text-white text-xs tracking-wide font-medium "><?php _e('Add New', 'aavoya-woocommerce-request-a-quote'); ?></button>
							</div>

						</div>
					</div>
				</div>

				<div id="aavoya-wraq-woocommerce" class="hidden tab-body-area border w-full  bg-white">
					<?php
					if (get_option('wraqwo')) {
						$wraqwochecked = 'checked';
						$hidden = '';
					} else {
						$wraqwochecked = '';
						$hidden = 'hidden';
					}
					?>
					<div class="woo-form-area">
						<div class="mt-2 border-b border-gray-100 py-3 px-6 flex items-center justify-between">
							<label class="text-xs tracking-wide font-medium" for="enable-woo-id"><span><?php _e('Enable for Woocommerce.', 'aavoya-woocommerce-request-a-quote'); ?></label>
							<input class="enable-woo-class" id="enable-woo-id" name="enable-woo" type="checkbox" <?php echo esc_attr($wraqwochecked); ?>>
						</div>
						<div class="py-2 px-2 woo-form-map-table">
							<div id="woo-default-setting-area" class="<?php echo esc_attr($hidden); ?> relative ">
								<div class="woo-appender">
									<?php if (aavoyaWooCom != true) {
										_e('WooCommerce not installed', 'aavoya-woocommerce-request-a-quote');
									} ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="aavoya-setting" class="hidden tab-body-area border w-full  bg-white">
					<?php
					$globalStyleArray = aavoya_get_global_data();
					?>
					<div class="setting-area">

						<div class="mt-2 border-b border-gray-100 py-3 px-6 flex items-center justify-between">
							<label class="text-xs tracking-wide font-medium"><span><?php _e('Settings', 'aavoya-woocommerce-request-a-quote'); ?></label>
						</div>

						<div class="py-1 px-1 ">
							<div class="relative ">
								<div class="setting-appender p-2">
									<div class="button-setting border rounded-sm shadow ">

										<div class="flex flex-row items-center px-2 py-3">
											<div class="flex justify-start w-1/2">
												<span class="text-xs tracking-wide font-medium">
													<?php _e('Default Button Setting', 'aavoya-woocommerce-request-a-quote'); ?>
												</span>
											</div>
											<div class="flex justify-end w-1/2 showhideaccordion cursor-pointer" data-target="showhidebuttonsettingarea">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 text-gray-400 duration-100">
													<path d="M10 0a10 10 0 110 20 10 10 0 010-20zM2 10a8 8 0 1016 0 8 8 0 00-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"></path>
												</svg>
											</div>
										</div>

										<div class="w-full px-2 py-2 border-t " id="showhidebuttonsettingarea" style="display:none;">
											<div class="w-full flex flex-row realtive z-30 rounded-sm flex-wrap bg-white setting-row ">
												<div class="setting-col w-full buttonexamplediv flex justify-center p-8">
													<button class="globalpreviewbutton" style="<?php echo esc_html(aavoya_global_data_to_inline_style()); ?>"><?php echo esc_html($globalStyleArray['globalbuttontext']); ?></button>
												</div>
												<div class="setting-col p-1 md:w-1/2">
													<lebel for="global-corner"><?php _e('Corners', 'aavoya-woocommerce-request-a-quote'); ?></lebel>
													<input type="range" name="corners" id="global-corner" max="100" mix="0" value="<?php echo intval($globalStyleArray['globalborderradiusvalue']); ?>">
												</div>
												<div class="setting-col p-1 md:w-1/2">
													<label class="block" for="global-b-text"><?php _e('Text', 'aavoya-woocommerce-request-a-quote'); ?></label>
													<input class="w-full" type="text" id="global-b-text" name="buttontext" value="<?php echo esc_textarea($globalStyleArray['globalbuttontext']); ?>">
												</div>
												<div class="setting-col p-1 md:w-1/2">
													<label for="global-padding-x"><?php _e('Padding X', 'aavoya-woocommerce-request-a-quote'); ?></label>
													<input type="range" name="global-padding-x" id="global-padding-x" max="100" mix="0" value="<?php echo intval($globalStyleArray['globalpaddingxvalue']); ?>">
												</div>
												<div class="setting-col p-1 md:w-1/2">
													<label for="global-padding-y"><?php _e('Padding Y', 'aavoya-woocommerce-request-a-quote'); ?></label>
													<input type="range" name="global-padding-y" id="global-padding-y" max="100" mix="0" value="<?php echo intval($globalStyleArray['globalpaddingyvalue']); ?>">
												</div>

												<div class="setting-col p-1 md:w-1/2">
													<label for="global-size"><?php _e('Size', 'aavoya-woocommerce-request-a-quote'); ?></lebel>
														<input type="range" name="global-size" id="global-size" max="100" mix="0" value="<?php echo intval($globalStyleArray['globalbuttonfontsize']); ?>">
												</div>
												<div class="setting-col p-1 md:w-1/2">
													<label for="global-tracking"><?php _e('Tracking', 'aavoya-woocommerce-request-a-quote'); ?></lebel>
														<input type="range" name="global-tracking" id="global-tracking" max="100" mix="0" value="<?php echo intval($globalStyleArray['globalbuttontracking']); ?>">
												</div>
												<div class="setting-col p-1 md:w-1/2 flex flex-wrap flex-row">
													<div class="sub-setting-col  md:w-1/2">
														<label class="block" forn="global-background-color"><?php _e('Background Color', 'aavoya-woocommerce-request-a-quote'); ?></label>
														<input type="color" name="global-background-color" id="global-background-color" value="<?php echo esc_attr($globalStyleArray['globalbuttonbgcolor']); ?>">
													</div>
													<div class="sub-setting-col md:w-1/2">
														<label class="block" for="global-text-color"><?php _e('Text color', 'aavoya-woocommerce-request-a-quote'); ?></label>
														<input type="color" name="global-text-color" id="global-text-color" value="<?php echo esc_attr($globalStyleArray['globalbuttontextcolor']); ?>">
													</div>
												</div>
												<div class="setting-col p-1 md:w-1/2">
													<label class="block" for="global-css-class"><?php _e('Css class', 'aavoya-woocommerce-request-a-quote'); ?></label>
													<input class="w-full" type="text" name="global-css-class" id="global-css-class" value="<?php echo esc_textarea($globalStyleArray['globalbuttoncssclass']); ?>">
												</div>
											</div>
											<div class="w-full mt-2 border-gray-100">
												<button class="save-settings rounded flex ml-auto mr-0 px-8 py-2 mt-2 bg-blue-900 text-white text-xs tracking-wide font-medium"><?php _e('Save', 'aavoya-woocommerce-request-a-quote'); ?> </button>
											</div>
										</div>


									</div>

								</div>
							</div>
						</div>

					</div>
				</div>

				<div id="aavoya-wraq-help" class="hidden tab-body-area border w-full bg-white">
					<div class="help-area">
						<div class="mt-2 border-b border-gray-100 py-3 px-6 flex items-center justify-between">
							<label class="text-xs tracking-wide font-medium"><span><?php _e('Help', 'aavoya-woocommerce-request-a-quote'); ?></label>
						</div>
						<div class="py-1 px-1 ">
							<div class="relative ">
								<div class="help-appender p-2">
									<div class="creating-buttons border rounded-sm shadow">

										<div class="flex flex-row items-center px-2 py-3">
											<div class="flex justify-start w-1/2">
												<span class="text-xs tracking-wide font-medium">
													<?php _e('Creating a Button', 'aavoya-woocommerce-request-a-quote'); ?>
												</span>
											</div>
											<div class="flex justify-end w-1/2 showhideaccordion cursor-pointer" data-target="showhidecreateanddeleteabutton">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 text-gray-400 duration-100">
													<path d="M10 0a10 10 0 110 20 10 10 0 010-20zM2 10a8 8 0 1016 0 8 8 0 00-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"></path>
												</svg>
											</div>
										</div>

										<div class="w-full px-2 py-2 border-t" id="showhidecreateanddeleteabutton" style="display:none;">
											<div class="w-full flex flex-row realtive z-30 rounded-sm flex-wrap bg-white setting-row ">
												<iframe width="560" height="315" src="https://www.youtube.com/embed/JHdNQgGNJEE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
											</div>

										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
		<!-- main container ends here -->

<?php
	}
}

/**
 * creating the object of above class
 */
$aavoya_woocommerce_request_a_quote_user_interface = new aavoya_wraqui();
