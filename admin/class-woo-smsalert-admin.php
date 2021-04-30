<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://xperts.club/
 * @since      1.0.0
 *
 * @package    Xc_Woo_Twilio
 * @subpackage Xc_Woo_Twilio/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Xc_Woo_Twilio
 * @subpackage Xc_Woo_Twilio/admin
 * @author     XpertsClub <admin@xperts.club>
 */
class WooSmsAlert_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__).'css/woo-woo-woo-smsalert-admin.css',
            [],
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__).'js/woo-woo-smsalert-admin.js',
            ['jquery'],
            $this->version,
            false
        );

        wp_localize_script(
            $this->plugin_name,
            "xc_woo_twilio",
            ['ajax_url' => admin_url('admin-ajax.php')]
        );

    }

    /**
     *
     */
    public function output_sections()
    {
        global $current_section;

        $sections = $this->get_sections();

        if (empty($sections) || 1 === sizeof($sections)) {
            return;
        }

        echo '<ul class="subsubsub">';

        $array_keys = array_keys($sections);

        foreach ($sections as $id => $label) {
            echo '<li><a href="' .
                 admin_url('admin.php?page=wc-settings&tab=woo-smsalert&section=' . sanitize_title($id)) . '" class="' . ($current_section == $id ? 'current' : '') .
                 '">' . $label . '</a> ' .
                 ( end($array_keys) == $id ? '' : '|') .
                 ' </li>';
        }

        echo '</ul><br class="clear" />';
    }


    /**
     * @return array
     */
    public function get_sections()
    {
        $sections = [
            ''         => __('SmsAlert Settings', 'woo-smsalert'),
            'messages' => __('Messages Configuration', 'woo-smsalert'),
        ];

        return $sections;
    }


    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     *
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     * @since    1.0.0
     */
    public static function add_settings_tab($settings_tabs)
    {
        $settings_tabs['woo-smsalert'] = __('SMS Notifications', 'woo-smsalert');

        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab()
    {
        global $current_section;
        woocommerce_admin_fields(WooSmsAlert_Admin::get_settings($current_section));
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings()
    {
        global $current_section;
        woocommerce_update_options(WooSmsAlert_Admin::get_settings($current_section));
    }


    /**
     * Get all the settings for this plugin for @return array Array of settings for @see woocommerce_admin_fields() function.
     * @see woocommerce_admin_fields() function.
     *
     */
    public static function get_settings($current_section = '')
    {
        if ($current_section == '') {
            $settings = [
                'section_title'        => [
                    'name' => __('SmsAlert Settings', 'woo-smsalert'),
                    'type' => 'title',
                    'desc' => '',
                    'id'   => 'woo-smsalert_section_title',
                ],
                '_woo-smsalert_sid'   => [
                    'name' => __('SmsAlert Account Sid', 'woo-smsalert'),
                    'type' => 'text',
                    'desc' => __('The account Sid for your account in Twilio', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_sid',
                ],
                '_woo-smsalert_token' => [
                    'name' => __('Authentication Token', 'woo-smsalert'),
                    'type' => 'text',
                    'desc' => __('The authentication Token for your account in SmsAlert', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_token',
                ],

//                '_woo-smsalert_whatsapp_number' => [
//                    'name' => __('WhatsApp Sender ID', 'woo-smsalert'),
//                    'type' => 'text',
//                    'desc' => __('The WhatsApp Sender ID of your account in Twilio', 'woo-smsalert'),
//                    'id'   => 'woo-smsalert_whatsapp_number',
//                ],

                '_woo-smsalert_sms_number' => [
                    'name' => __('SMS Sender ID', 'woo-smsalert'),
                    'type' => 'text',
                    'desc' => __('The SMS Sender ID of your account in Twilio', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_sms_number',
                ],

                '_woo-smsalert_admin_number' => [
                    'name' => __('Admin Mobile Number', 'woo-smsalert'),
                    'type' => 'text',
                    'desc' => __('Admin mobile number to get order notifications', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_admin_number',
                ],

                '_woo-smsalert_default_type' => [
                    'name'    => __('Default notification type', 'woo-smsalert'),
                    'type'    => 'radio',
                    'options' => ["sms" => "SMS", "whatsapp" => "WhatsApp"],
                    'id'      => 'woo-smsalert_default_type',
                ],

                'section_end' => [
                    'type' => 'sectionend',
                    'id'   => 'woo-smsalert_section_end',
                ],
            ];
        } elseif ($current_section == "messages") {
            $settings = [
                [
                    'name' => __('New Order Notification', 'woo-smsalert'),
                    'type' => 'title',
                    'desc' => __('This notification will send to admin when a new order was placed', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_section_messages_start',
                ],
                [
                    'name' => __('New Order Notification', 'woo-smsalert'),
                    'type' => 'checkbox',
                    'desc' => __('Enable New Order Notification', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_neworder',
                ],
                [
                    'name'    => __('Message', 'woo-smsalert'),
                    'type'    => 'textarea',
                    'desc'    => __(
                        'Available placeholders: {billing_first_name} , {billing_last_name},  {order_number} , {order_total}, {site_title}',
                        'woo-smsalert'
                    ),
                    'css'     => "height:100px",
                    'default' => "A new order was placed by {billing_first_name}  {billing_last_name}, Order Id : {order_number} Order Amount : {order_total}",
                    'id'      => 'woo-smsalert_neworder_message',
                ],
                [
                    'type' => 'sectionend',
                    'id'   => 'woo-smsalert_section_messages_end',
                ],
            ];

            $status = wc_get_order_statuses();
            $prefix = 'wc-';
            foreach ($status as $key => $val) {
                if (substr($key, 0, strlen($prefix)) == $prefix) {
                    $key = substr($key, strlen($prefix));
                }

                $temp = [
                    [
                        'name' => sprintf("%s %s", __('Send Notification when Order is', 'woo-smsalert'), $val),
                        'type' => 'title',
                        'desc' => sprintf(
                            "%s %s",
                            __('This notification will send when a order status is changed to', 'woo-smsalert'),
                            $val
                        ),
                        'id'   => 'woo-smsalert_section_messages_start',
                    ],
                    [
                        'name' => __('Send notification to admin', 'woo-smsalert'),
                        'type' => 'checkbox',
                        'desc' => sprintf("%s %s", __('Send notification to admin when ouder is', 'woo-smsalert'), $val),
                        'id'   => 'woo-smsalert_admin_enable_order_status_'.$key,
                    ],
                    [
                        'name'    => __('Admin message', 'woo-smsalert'),
                        'type'    => 'textarea',
                        'desc'    => __(
                            'Available placeholders: {order_status}, {billing_first_name} , {billing_last_name},  {order_number} , {order_total}, {site_title}',
                            'woo-smsalert'
                        ),
                        'css'     => "height:100px",
                        'default' => "{site_title}: status of order #{order_number} has been changed to {order_status}.",
                        'id'      => 'woo-smsalert_admin_message_'.$key,
                    ],
                    [
                        'name' => __('Send notification to customer', 'woo-smsalert'),
                        'type' => 'checkbox',
                        'desc' => sprintf("%s %s", __('Send notification to customer when ouder is', 'woo-smsalert'), $val),
                        'id'   => 'woo-smsalert_customer_enable_order_status_'.$key,
                    ],
                    [
                        'name'    => __('Customer message', 'woo-smsalert'),
                        'type'    => 'textarea',
                        'desc'    => __(
                            'Available placeholders: {order_status}, {billing_first_name} , {billing_last_name},  {order_number} , {order_total}, {site_title}',
                            'woo-smsalert'
                        ),
                        'css'     => "height:100px",
                        'default' => "Hello {billing_first_name}, status of your order {order_number} with {site_title} has been changed to {order_status}.",
                        'id'      => 'woo-smsalert_customer_message_'.$key,
                    ],
                    [
                        'type' => 'sectionend',
                        'id'   => 'woo-smsalert_section_messages_end',
                    ],
                ];

                $settings = array_merge($settings, $temp);

            }

            $temp = [
                [
                    'name' => __('Send Notification when a new note is added to order', 'woo-smsalert'),
                    'type' => 'title',
                    'desc' => __('This notification will send when a new note is added to order', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_section_messages_start_new_note',
                ],
                [
                    'name' => __('Send notification to customer', 'woo-smsalert'),
                    'type' => 'checkbox',
                    'desc' => __('Send notification to customer when a new note is added to order', 'woo-smsalert'),
                    'id'   => 'woo-smsalert_customer_enable_order_note',
                ],
                [
                    'name'    => __('Customer message', 'woo-smsalert'),
                    'type'    => 'textarea',
                    'desc'    => __(
                        'Available placeholders: {order_note}, {billing_first_name} , {billing_last_name},  {order_number} , {order_total}, {site_title}',
                        'woo-smsalert'
                    ),
                    'css'     => "height:100px",
                    'default' => "Hello {billing_first_name}, a new note has been added to your order {order_number}: {order_note}",
                    'id'      => 'woo-smsalert_customer_message_note',
                ],
                [
                    'type' => 'sectionend',
                    'id'   => 'woo-smsalert_section_messages_end',
                ],
            ];

            $settings = array_merge($settings, $temp);


        }

        return apply_filters('wc_settings_tab_woo-smsalert_settings', $settings);
    }

    public function plugin_action_links($links)
    {
        $action_links = [
            'settings' => '<a href="'.admin_url(
                    'admin.php?page=wc-settings&tab=woo-smsalert'
                ).'" aria-label="'.esc_attr__('settings', 'woo-smsalert').'">'.esc_html__('Settings', 'woo-smsalert').'</a>',
        ];

        return array_merge($action_links, $links);
    }

    public function add_order_meta_box()
    {

        add_meta_box(
            'woo-smsalert_order_meta_box',
            esc_html__('SMS/WhatsApp Messages', 'woo-smsalert'),
            [$this, 'display_order_meta_box'],
            'shop_order',
            'side',
            'default'
        );
    }

    public function display_order_meta_box($post)
    {
        ?>
        <p><?php echo esc_attr__('Message', 'woo-smsalert'); ?></p>
        <p><textarea type="text" name="woo-smsalert_order_message" id="woo-smsalert_order_message" class="input-text"
                     style="width: 100%;" rows="4"></textarea></p>
        <div class="woo-smsalert_order_message_buttons">
            <a data-order-id="<?php echo $post->ID; ?>" class="button woo-smsalert_order_message_buttons_load_message"
               href="jvascript:void(0)"><?php echo esc_attr__('Load Order Status Message', 'woo-smsalert'); ?></a>
            <a data-order-id="<?php echo $post->ID; ?>"
               class="button woo-smsalert_order_message_buttons_send_message button-primary"
               href="jvascript:void(0)"><?php echo esc_attr__('Send', 'woo-smsalert'); ?></a>
        </div>
        <?php
    }

    public function smsalert_order_message_load_message()
    {
        $order_id     = $_POST['order_id'];
        $order        = wc_get_order($order_id);
        $order_status = $order->get_status();
        $message      = get_option("woo-smsalert_customer_message_{$order_status}");

        wp_send_json_success(['message' => $message]);
    }

    public function smsalert_order_message_send_message()
    {
        $order_id = $_POST['order_id'];
        $message  = $_POST['message'];
        ob_start();
        do_action('woo-smsalert_custom_message', $order_id, $message);
        $message = ob_get_clean();

        wp_send_json_success(['message' => $message]);
    }

}
