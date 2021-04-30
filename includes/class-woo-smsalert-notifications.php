<?php

/**
 * The SMSAlert messages functionality of the plugin.
 * @link       https://smsalert.mobi/
 * @since      1.0.0
 *
 * @package    WooSmsALlert
 */

use GuzzleHttp\Client;

/**
 * The SMSAlert messages functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooSmsALlert
 * @subpackage WooSmsALlert/includes
 * @author     SMSALERT.MOBI <contact@smsalert.mobi>
 */
class WooSmsAlert_Notifications
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
     *
     * @since    1.0.0
     * @access   private
     * @var      string $sid SMSAlert SID
     */
    private $username;

    /**.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $token SMSAlert token
     */
    private $token;

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

        $this->username = get_option('woo_smsalert_username', '');
        $this->token    = get_option('woo_smsalert_token', '');

    }

    /**
     * Get WordPress blog name.
     *
     * @return string
     */
    public function get_blogname()
    {
        return wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }


    /**
     * @Returns the country prefix code
     *
     * @aince 1.0.0
     * @return mixed   $code/codes
     * @var    string $country
     */
    function get_country_code($country = '')
    {
        $codes = [
            'RO' => '40',
        ];

        return ($country == '') ? $codes : (isset($codes[$country]) ? $codes[$country] : '');
    }

    /**
     * @param $message
     * @param $placeholders
     * @param $number
     *
     * @return array|void
     */
    public function send_message($message, $placeholders, $number)
    {
        if (empty($message) || empty($number)) {
            return [
                'error'   => esc_attr__('Empty phone number or Empty message', 'woo_smsalert'),
                'status'  => 'faild',
                'message' => $message,
            ];
        }
        $message = strtr($message, $placeholders);
        $message = strip_tags($message);

        return $this->send($message, $number);
    }

    /**
     * @param $message
     * @param $number
     * @param $type
     *
     * @return array|void
     */
    public function send($message, $number)
    {
        try {
            if (empty($this->username) || empty($this->token)) {
                return;
            }

            $client = new Client(
                [
                    // Base URI is used with relative requests
                    'base_uri' => 'https://smsalert.mobi',
                    // You can set any number of default request options.
                    'timeout'  => 2.0,
                ]
            );

            $response = $client->request(
                'POST',
                '/api/sms/send',
                [
                    'form_params' => [
                        'username' => $this->username,
                        'apiKey'   => $this->token,
                        'tel'      => $number,
                        'message'  => $message,
                    ],
                ]
            );

            $status = json_decode($response->getBody()->getContents(), true);

            return ['message' => $message, 'status' => 'success'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage(), 'status' => 'faild', 'message' => $message];
        }

    }

    /**
     * @ Returns all placeholders with values
     *
     * @return array     $placeholders
     * @var    mixed $order
     * @since  1.0.0
     * @access public
     */
    public function placeholders($order)
    {
        $placeholders                 = [];
        $placeholders['{site_title}'] = $this->get_blogname();
        if (is_a($order, 'WC_Order')) {
            $placeholders['{order_number}']       = $order->get_order_number();
            $placeholders['{billing_first_name}'] = $order->get_billing_first_name();
            $placeholders['{billing_last_name}']  = $order->get_billing_last_name();
            $placeholders['{order_total}']        = html_entity_decode(wc_price($order->get_total()));
            $placeholders['{order_date}']         = $order->get_date_created();
        }

        $placeholders = apply_filters('woo_smsalert_placeholders', $placeholders, $order);

        return $placeholders;
    }


    /**
     * @Returns customer mobile number.
     *
     * @return   string   $mobile
     * @var      mixed $order
     * @since    1.0.0
     * @access   public
     */
    public function get_customer_mobile($order)
    {
        $mobile          = $order->get_billing_phone();
        $billing_country = $order->get_billing_country();
        $country_code    = $this->get_country_code($billing_country);

        preg_match("/(\d{1,4})[0-9.\- ]+/", $mobile, $phone_prefix);
        if (empty($phone_prefix)) {
            return;
        }

        if (isset($country_code)) {
            if (strpos(strval($phone_prefix[1]), strval($country_code)) === false) {
                $mobile = $country_code.ltrim($mobile, '0');
            }
        }

        $mobile = '+'.$mobile;

        return $mobile;
    }

    /**
     * @param       $order_id
     * @param false $order
     */
    public function new_order_notification($order_id, $order = false)
    {
        if ($order_id && !is_a($order, 'WC_Order')) {
            $order = wc_get_order($order_id);
        }
        $placeholders = $this->placeholders($order);
        $message      = get_option('woo_smsalert_neworder_message', '');
        if (!empty($message)) {
            $mobile = get_option('woo_smsalert_admin_number', '');
            $status = $this->send_message($message, $placeholders, $mobile);
            $this->add_order_note($order, $status);
        }
    }

    /**
     * @param $order_id
     * @param $old_status
     * @param $new_status
     * @param $order
     */
    public function notification_after_order($order_id, $old_status, $new_status, $order)
    {
        if (!$order_id) {
            return;
        }
        if (empty($new_status)) {
            return;
        }

        $statuses = wc_get_order_statuses();
        $status   = 'wc-' === substr($new_status, 0, 3) ? substr($new_status, 3) : $new_status;
        $status   = isset($statuses['wc-'.$status]) ? $statuses['wc-'.$status] : $status;

        if ('yes' == get_option("woo_smsalert_admin_enable_order_status_{$new_status}", '')) {
            $message = get_option("woo_smsalert_admin_message_{$new_status}", '');
            if (!empty($message)) {
                $mobile                         = get_option('woo_smsalert_admin_number', '');
                $placeholders                   = $this->placeholders($order);
                $placeholders['{order_status}'] = $status;
                $status                         = $this->send_message($message, $placeholders, $mobile);
                $this->add_order_note($order, $status);
            }
        }

        if ('yes' == get_option("woo_smsalert_customer_enable_order_status_{$new_status}", '')) {
            $message = get_option("woo_smsalert_customer_message_{$new_status}", '');
            if (!empty($message)) {
                $mobile                         = $this->get_customer_mobile($order);
                $placeholders                   = $this->placeholders($order);
                $placeholders['{order_status}'] = $status;
                $status                         = $this->send_message($message, $placeholders, $mobile);
                $this->add_order_note($order, $status);
            }
        }
    }

    /**
     * @param $data
     */
    public function new_customer_note($data)
    {
        if ('yes' == get_option("woo_smsalert_customer_enable_order_note", '')) {

            $message  = get_option("woo_smsalert_customer_message_note", '');
            $order_id = $data['order_id'];
            $order    = new WC_Order($order_id);

            if (!empty($message)) {
                $mobile                       = $this->get_customer_mobile($order);
                $placeholders                 = $this->placeholders($order);
                $placeholders['{order_note}'] = $data['customer_note'];
                $status                       = $this->send_message($message, $placeholders, $mobile);
                $this->add_order_note($order, $status);
            }
        }
    }

    public function add_order_note($order, $status)
    {
        $note = esc_attr__('Message : ', 'woo-smsalert');
        $note .= $status['message'];

        if ($status['status'] == 'success') {
            $note .= '<br>'.esc_attr__('Status : Success ', 'woo-smsalert');
        } else {
            $note .= '<br>'.esc_attr__('Status : Faild ', 'woo-smsalert');
            $note .= '<br>'.esc_attr__('Error : ', 'woo-smsalert');
            $note .= $status['error'];
        }

        $order->add_order_note($note);
        $order->save();
    }

    /**
     * @param $order_id
     * @param $message
     */
    public function custom_order_message($order_id, $message)
    {
        $order = new WC_Order($order_id);
        $note  = '';
        if (!empty($message)) {
            $order_status                   = $order->get_status();
            $mobile                         = $this->get_customer_mobile($order);
            $placeholders                   = $this->placeholders($order);
            $placeholders['{order_status}'] = $order_status;
            //$placeholders['{order_note}']   = $data['customer_note'];
            $status = $this->send_message($message, $placeholders, $mobile);
            $this->add_order_note($order, $status);
            if ($status['status'] == 'success') {
                $note = esc_attr__('Message Sent : ', 'woo-smsalert');
                $note .= $status['message'];
            } else {
                $note = esc_attr__('Message Failed, Error : ', 'woo-smsalert');
                $note .= $status['error'];
            }
        }
        echo $note;
    }

}