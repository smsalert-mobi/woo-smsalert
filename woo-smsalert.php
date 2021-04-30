<?php
/**
 * The plugin bootstrap file
 *
 *
 * @link              https://xperts.club/
 * @since             1.0.0
 * @package           Xc_Woo_Twilio
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce SMS/WhatsApp Notifications
 * Plugin URI:        https://wp.xperts.club/woonotifications/
 * Description:       By using Woocommerce SMS/WhatsApp Notifications you can start sending custom SMS/WhatsApp messages to your customers right away, informing them of any change in the status of the order they placed. Also, you can receive an SMS/WhatsApp message when the shop gets a new order.
 * Version:           1.0.2
 * Author:            XpertsClub
 * Author URI:        https://xperts.club/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xc-woo-twilio
 * Domain Path:       /languages
 * Requires at least: 4.0
 * Tested up to:      5.6
 * WC requires at least: 3.5.0
 * WC tested up to: 4.9.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOO_SMSALERT_VERSION', '1.0.2');


// Define XCWPP_PLUGIN_FILE.
if (!defined('WOO_SMSALERT_FILE')) {
    define('WOO_SMSALERT_FILE', __FILE__);
}

if (!defined('WOO_SMSALERT_BASENAME')) {
    define('WOO_SMSALERT_BASENAME', plugin_basename(WOO_SMSALERT_FILE));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xc-woo-twilio-activator.php
 */
function activate_woo_smsalert()
{
    require_once plugin_dir_path(__FILE__).'includes/class-woo-smsalert-activator.php';
    WooSmsAlert_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-xc-woo-twilio-deactivator.php
 */
function deactivate_woo_smsalert()
{
    require_once plugin_dir_path(__FILE__).'includes/class-woo-smsalert-deactivator.php';
    WooSmsAlert_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woo_smsalert');
register_deactivation_hook(__FILE__, 'deactivate_woo_smsalert');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-woo-smsalert.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_smsalert()
{

    $plugin = new WooSmsAlert();
    $plugin->run();

}

/*
* check Woocommerce Activation
*/
include_once(ABSPATH.'wp-admin/includes/plugin.php');
if (is_plugin_active('woocommerce/woocommerce.php')) {
    // run the plugin
    run_woo_smsalert();
} else {
    // display notices to admin
    add_action('admin_notices', 'woo_smsalert_installed_notice');
}

/**
 *
 */
function woo_smsalert_installed_notice()
{
    ?>
    <div class="error">
        <p><?php _e(
                'Woocommerce SMS Notifications requires the WooCommerce plugin. Please install or activate Woocommerce before!',
                'woo-smsalert'
            ); ?></p>
    </div>
    <?php
}