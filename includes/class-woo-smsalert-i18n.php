<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://smsalert.mobi/
 * @since      1.0.0
 *
 * @package    WooSmsALlert
 * @subpackage WooSmsALlert/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WooSmsALlert
 * @subpackage WooSmsALlert/includes
 * @author     SMSALERT.MOBI <contact@smsalert.mobi>
 */
class WooSmsAlert_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'woo_smsalert',
            false,
            dirname(dirname(plugin_basename(__FILE__))).'/languages/'
        );

    }


}
