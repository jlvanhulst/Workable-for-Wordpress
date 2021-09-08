<?php
/**
 * Define the internationalization functionality.
 *
 * @link       https://www.kanopistudios.com
 * @since      1.0.0
 *
 * @package    Workable_Api
 * @subpackage Workable_Api/inc
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Workable_Api
 * @subpackage Workable_Api/inc
 * @author     Katherine White <katherine@kanopistudios.com>
 */
class Workable_Api_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'workable-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
