<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://smsalert.mobi/
 * @since      1.0.0
 *
 * @package    WooSmsALlert
 * @subpackage WooSmsALlert/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WooSmsALlert
 * @subpackage WooSmsALlert/includes
 * @author     SMSALERT.MOBI <contact@smsalert.mobi>
 */
class WooSmsAlert
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WooSmsAlert_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;


    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WOO_SMSALERT_VERSION')) {
            $this->version = WOO_SMSALERT_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'woo-smsalert';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_notification_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - WooSmsAlert_Loader. Orchestrates the hooks of the plugin.
     * - WooSmsAlert_i18n. Defines internationalization functionality.
     * - WooSmsAlert_Admin. Defines all hooks for the admin area.
     * - WooSmsAlert_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/class-woo-smsalert-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/class-woo-smsalert-i18n.php';

        /**
         * Include autoload
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/vendor/autoload.php';

        /**
         * The class responsible for send all notifications
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/class-woo-smsalert-notifications.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'admin/class-woo-smsalert-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'public/class-woo-smsalert-public.php';

        $this->loader = new WOoSmsAlert_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WooSmsAlert_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new WooSmsAlert_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new WooSmsAlert_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');


        $this->loader->add_filter('plugin_action_links_'.WOO_SMSALERT_BASENAME, $plugin_admin, 'plugin_action_links');

        $this->loader->add_filter('woocommerce_settings_tabs_array', $plugin_admin, 'add_settings_tab', 50);

        $this->loader->add_action('woocommerce_sections_woo_smsalert', $plugin_admin, 'output_sections');

        $this->loader->add_action('woocommerce_settings_tabs_woo_smsalert', $plugin_admin, 'settings_tab');
        $this->loader->add_action('woocommerce_update_options_woo_smsalert', $plugin_admin, 'update_settings');

        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_order_meta_box');
        $this->loader->add_action(
            'wp_ajax_woo_smsalert_order_message_load_message',
            $plugin_admin,
            'woo_smsalert_order_message_load_message'
        );
        $this->loader->add_action(
            'wp_ajax_woo_smsalert_order_message_send_message',
            $plugin_admin,
            'woo_smsalert_order_message_send_message'
        );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new WooSmsAlert_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

    }

    /**
     * Register all of the hooks related to the notifications functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_notification_hooks()
    {
        $notifications = new WooSmsAlert_Notifications($this->get_plugin_name(), $this->get_version());

        if ('yes' === get_option('woo_smsalert_neworder', '')) {

            // Triggers for this email.
            $sttus = [
                "woocommerce_order_status_pending_to_processing_notification",
                "woocommerce_order_status_pending_to_completed_notification",
                "woocommerce_order_status_pending_to_on-hold_notification",
                "woocommerce_order_status_failed_to_processing_notification",
                "woocommerce_order_status_failed_to_completed_notification",
                "woocommerce_order_status_failed_to_on-hold_notification",
                "woocommerce_order_status_cancelled_to_processing_notification",
                "woocommerce_order_status_cancelled_to_completed_notification",
                "woocommerce_order_status_cancelled_to_on-hold_notification",
            ];
            foreach ($sttus as $st) {
                $this->loader->add_action($st, $notifications, 'new_order_notification', 10, 2);
            }
        }

        $this->loader->add_action(
            'woocommerce_order_status_changed',
            $notifications,
            'notification_after_order',
            10,
            4
        );

        $this->loader->add_action('woocommerce_new_customer_note', $notifications, 'new_customer_note', 10);

        $this->loader->add_action('woo_smsalert_custom_message', $notifications, 'custom_order_message', 10, 2);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    WooSmsAlert_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

}
