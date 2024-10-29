<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
 * base
 * This class to be extended
 */
class base
{
	public function __construct()
	{
	}

	/**
	 * load
	 * loader method. non overridable 
	 * @param  string $argument
	 * @return object $argument
	 */
	public final function load($argument = null, $filedir = null)
	{

		if ($argument == null) {
			return;
		}

		if ($filedir == null) {

			$filename = __DIR__ . '/' . trim($argument) . '.php';
		} else {

			$filename = $filedir . '/' . trim($argument) . '.php';
		}


		if (!file_exists($filename)) {
			return "No Such File:" . $filename;
		}

		include_once $filename;
		$$argument = new $argument;

		return $$argument;
	}
}
