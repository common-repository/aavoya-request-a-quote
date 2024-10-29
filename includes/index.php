<?php

if (!defined('ABSPATH')) {
	exit;
}

//Base class to load other classes and to provide an object(name will same as class name provided) with its load method 
require_once aavoyaWraqAbsolute . 'includes/base.php';

//This to register custom post-type for aavoya request  a quote
require_once aavoyaWraqAbsolute . 'includes/aavoya_wraqrpt.php';

// This to add init data to the system
require_once aavoyaWraqAbsolute . 'includes/aavoya_initdata.php';

//This to include all the helper function
require_once aavoyaWraqAbsolute . 'includes/aavoya_wraqhelper.php';

// This to handle all ajax calls
require_once aavoyaWraqAbsolute . 'includes/aavoya_wraquiajax.php';

//For Setting 
require_once aavoyaWraqAbsolute . 'includes/aavoya_wraqui.php';
