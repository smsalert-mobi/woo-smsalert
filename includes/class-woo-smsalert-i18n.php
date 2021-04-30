<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://xperts.club/
 * @since      1.0.0
 *
 * @package    Xc_Woo_Twilio
 * @subpackage Xc_Woo_Twilio/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Xc_Woo_Twilio
 * @subpackage Xc_Woo_Twilio/includes
 * @author     XpertsClub <admin@xperts.club>
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
            'xc-woo-twilio',
            false,
            dirname(dirname(plugin_basename(__FILE__))).'/languages/'
        );

    }


}
