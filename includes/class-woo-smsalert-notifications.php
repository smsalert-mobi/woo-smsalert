<?php
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;
/**
 * The Twilio messages functionality of the plugin.
 *
 * @link       https://xperts.club/
 * @since      1.0.0
 *
 * @package    Xc_Woo_Twilio
 * @subpackage Xc_Woo_Twilio/includes
 */

/**
 * The Twilio messages functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Xc_Woo_Twilio
 * @subpackage Xc_Woo_Twilio/includes
 * @author     XpertsClub <admin@xperts.club>
 */
class WooSmsAlert_Notifications {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The Twilio SID.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sid    Twilio SID
	 */
	private $sid;
	
	/**
	 * The Twilio token.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $token    Twilio token
	 */
	private $token;
	
	/**
	 * The Twilio whatsapp_sender.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $whatsapp_sender    Twilio whatsapp_sender
	 */
	private $whatsapp_sender;
	
	/**
	 * The Twilio sms_sender.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sms_sender    Twilio sms_sender
	 */
	private $sms_sender;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		$this->sid = get_option('xc_woo_twilio_sid','');
		$this->token = get_option('xc_woo_twilio_token','');
		$this->whatsapp_sender = get_option('xc_woo_twilio_whatsapp_number','');
		$this->sms_sender = get_option('xc_woo_twilio_sms_number','');

	}
	
	/**
	 * Get WordPress blog name.
	 *
	 * @return string
	 */
	public function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}
	
	
	/**
	* @Returns the country prefix code
	*
	* @aince 1.0.0
	* @var    string  $country
	* @return mixed   $code/codes
	*/
	function get_country_code( $country = '' ) {
		$codes = array( 
			'AC' => '247', 
			'AD' => '376', 
			'AE' => '971', 
			'AF' => '93', 
			'AG' => '1268', 
			'AI' => '1264', 
			'AL' => '355', 
			'AM' => '374', 
			'AO' => '244', 
			'AQ' => '672', 
			'AR' => '54', 
			'AS' => '1684', 
			'AT' => '43', 
			'AU' => '61', 
			'AW' => '297', 
			'AX' => '358', 
			'AZ' => '994', 
			'BA' => '387', 
			'BB' => '1246', 
			'BD' => '880', 
			'BE' => '32', 
			'BF' => '226', 
			'BG' => '359', 
			'BH' => '973', 
			'BI' => '257', 
			'BJ' => '229', 
			'BL' => '590', 
			'BM' => '1441', 
			'BN' => '673', 
			'BO' => '591', 
			'BQ' => '599', 
			'BR' => '55', 
			'BS' => '1242', 
			'BT' => '975', 
			'BW' => '267', 
			'BY' => '375', 
			'BZ' => '501', 
			'CA' => '1', 
			'CC' => '61', 
			'CD' => '243', 
			'CF' => '236', 
			'CG' => '242', 
			'CH' => '41', 
			'CI' => '225', 
			'CK' => '682', 
			'CL' => '56', 
			'CM' => '237', 
			'CN' => '86', 
			'CO' => '57', 
			'CR' => '506', 
			'CU' => '53', 
			'CV' => '238', 
			'CW' => '599', 
			'CX' => '61', 
			'CY' => '357', 
			'CZ' => '420', 
			'DE' => '49', 
			'DJ' => '253', 
			'DK' => '45', 
			'DM' => '1767', 
			'DO' => '1809', 
			'DO' => '1829', 
			'DO' => '1849', 
			'DZ' => '213', 
			'EC' => '593', 
			'EE' => '372', 
			'EG' => '20', 
			'EH' => '212', 
			'ER' => '291', 
			'ES' => '34', 
			'ET' => '251', 
			'EU' => '388', 
			'FI' => '358', 
			'FJ' => '679', 
			'FK' => '500', 
			'FM' => '691', 
			'FO' => '298', 
			'FR' => '33', 
			'GA' => '241', 
			'GB' => '44', 
			'GD' => '1473', 
			'GE' => '995', 
			'GF' => '594', 
			'GG' => '44', 
			'GH' => '233', 
			'GI' => '350', 
			'GL' => '299', 
			'GM' => '220', 
			'GN' => '224', 
			'GP' => '590', 
			'GQ' => '240', 
			'GR' => '30', 
			'GT' => '502', 
			'GU' => '1671', 
			'GW' => '245', 
			'GY' => '592', 
			'HK' => '852', 
			'HN' => '504', 
			'HR' => '385', 
			'HT' => '509', 
			'HU' => '36', 
			'ID' => '62', 
			'IE' => '353', 
			'IL' => '972', 
			'IM' => '44', 
			'IN' => '91', 
			'IO' => '246', 
			'IQ' => '964', 
			'IR' => '98', 
			'IS' => '354', 
			'IT' => '39', 
			'JE' => '44', 
			'JM' => '1876', 
			'JO' => '962', 
			'JP' => '81', 
			'KE' => '254', 
			'KG' => '996', 
			'KH' => '855', 
			'KI' => '686', 
			'KM' => '269', 
			'KN' => '1869', 
			'KP' => '850', 
			'KR' => '82', 
			'KW' => '965', 
			'KY' => '1345', 
			'KZ' => '7', 
			'LA' => '856', 
			'LB' => '961', 
			'LC' => '1758', 
			'LI' => '423', 
			'LK' => '94', 
			'LR' => '231', 
			'LS' => '266', 
			'LT' => '370', 
			'LU' => '352', 
			'LV' => '371', 
			'LY' => '218', 
			'MA' => '212', 
			'MC' => '377', 
			'MD' => '373', 
			'ME' => '382', 
			'MF' => '590', 
			'MG' => '261', 
			'MH' => '692', 
			'MK' => '389', 
			'ML' => '223', 
			'MM' => '95', 
			'MN' => '976', 
			'MO' => '853', 
			'MP' => '1670', 
			'MQ' => '596', 
			'MR' => '222', 
			'MS' => '1664', 
			'MT' => '356', 
			'MU' => '230', 
			'MV' => '960', 
			'MW' => '265', 
			'MX' => '52', 
			'MY' => '60', 
			'MZ' => '258', 
			'NA' => '264', 
			'NC' => '687', 
			'NE' => '227', 
			'NF' => '672', 
			'NG' => '234', 
			'NI' => '505', 
			'NL' => '31', 
			'NO' => '47', 
			'NP' => '977', 
			'NR' => '674', 
			'NU' => '683', 
			'NZ' => '64', 
			'OM' => '968', 
			'PA' => '507', 
			'PE' => '51', 
			'PF' => '689', 
			'PG' => '675', 
			'PH' => '63', 
			'PK' => '92', 
			'PL' => '48', 
			'PM' => '508', 
			'PR' => '1787', 
			'PR' => '1939', 
			'PS' => '970', 
			'PT' => '351', 
			'PW' => '680', 
			'PY' => '595', 
			'QA' => '974', 
			'QN' => '374', 
			'QS' => '252', 
			'QY' => '90', 
			'RE' => '262', 
			'RO' => '40', 
			'RS' => '381', 
			'RU' => '7', 
			'RW' => '250', 
			'SA' => '966', 
			'SB' => '677', 
			'SC' => '248', 
			'SD' => '249', 
			'SE' => '46', 
			'SG' => '65', 
			'SH' => '290', 
			'SI' => '386', 
			'SJ' => '47', 
			'SK' => '421', 
			'SL' => '232', 
			'SM' => '378', 
			'SN' => '221', 
			'SO' => '252', 
			'SR' => '597', 
			'SS' => '211', 
			'ST' => '239', 
			'SV' => '503', 
			'SX' => '1721', 
			'SY' => '963', 
			'SZ' => '268', 
			'TA' => '290', 
			'TC' => '1649', 
			'TD' => '235', 
			'TG' => '228', 
			'TH' => '66', 
			'TJ' => '992', 
			'TK' => '690', 
			'TL' => '670', 
			'TM' => '993', 
			'TN' => '216', 
			'TO' => '676', 
			'TR' => '90', 
			'TT' => '1868', 
			'TV' => '688', 
			'TW' => '886', 
			'TZ' => '255', 
			'UA' => '380', 
			'UG' => '256', 
			'UK' => '44', 
			'US' => '1', 
			'UY' => '598', 
			'UZ' => '998', 
			'VA' => '379', 
			'VA' => '39', 
			'VC' => '1784', 
			'VE' => '58', 
			'VG' => '1284', 
			'VI' => '1340', 
			'VN' => '84', 
			'VU' => '678', 
			'WF' => '681', 
			'WS' => '685', 
			'XC' => '991', 
			'XD' => '888', 
			'XG' => '881', 
			'XL' => '883', 
			'XN' => '857', 
			'XN' => '858', 
			'XN' => '870', 
			'XP' => '878', 
			'XR' => '979', 
			'XS' => '808', 
			'XT' => '800', 
			'XV' => '882', 
			'YE' => '967', 
			'YT' => '262', 
			'ZA' => '27', 
			'ZM' => '260', 
			'ZW' => '263' 
		);
	
		return ( $country == '' ) ? $codes : ( isset( $codes[$country] ) ? $codes[$country] : '' );
	}
	
	public function send_message($message, $placeholders, $number){
		if(empty($message) || empty($number) ){
			 return array('error' => esc_attr__( 'Empty phone number or Empty message', 'xc-woo-twilio' ), 'status' => 'faild', 'message' => $message);
		}
		$message_type = get_option("xc_woo_twilio_default_type", 'sms');
		$message = strtr($message, $placeholders);
		$message = strip_tags($message);
		return $this->send($message, $number, $message_type);
	}
	
	public function send( $message, $number, $type ){
		try{
			if( empty($this->sid) || empty($this->token)) return; 
			
			$message_client = new Client($this->sid, $this->token);
			
			switch($type){
				case "whatsapp":
					$prefix = 'whatsapp:';
					$sender = $this->whatsapp_sender;
				break;
				default:
					$prefix = '';
					$sender = $this->sms_sender;
				break;	
			}
			
			// Use the client to do fun stuff like send text messages!
			$ret = $message_client->messages->create(
				// the number you'd like to send the message to
				$prefix.$number,
				array(
					// A Twilio phone number you purchased at twilio.com/console
					'from' => $prefix.$sender,
					// the body of the text message you'd like to send
					'body' => $message
				)
			);
			return array('message' => $message, 'status' => 'success');
		}
		catch(Exception $e){
			return array('error' => $e->getMessage(), 'status' => 'faild', 'message' => $message);
		}
				
	}
	
	/**
	* @ Returns all placeholders with values 
	*
	* @since  1.0.0
	* @access public
	* @var    mixed     $order
	* @return array     $placeholders
	*/
	public function placeholders( $order ){
		$placeholders = array();
		$placeholders['{site_title}'] = $this->get_blogname();
		if ( is_a( $order, 'WC_Order' ) ) {
			$placeholders['{order_number}'] = $order->get_order_number();
			$placeholders['{billing_first_name}'] = $order->get_billing_first_name();
			$placeholders['{billing_last_name}'] = $order->get_billing_last_name();
			$placeholders['{order_total}'] = html_entity_decode(wc_price($order->get_total()));
			$placeholders['{order_date}'] = $order->get_date_created();
		}
		
		$placeholders = apply_filters('xc_woo_twilio_placeholders', $placeholders, $order);
		
		return $placeholders;
	}
	
	
	/**
	* @Returns customer mobile number.
	*
	* @since    1.0.0
	* @access   public
	* @var      mixed    $order    
	* @return   string   $mobile    
	*/
	public function get_customer_mobile( $order ){
		$mobile = $order->get_billing_phone();
		$billing_country = $order->get_billing_country();
		$country_code = $this->get_country_code($billing_country);
		
		preg_match( "/(\d{1,4})[0-9.\- ]+/", $mobile, $phone_prefix );
		if ( empty( $phone_prefix ) ) {
			return;
		}
		
		if ( isset( $country_code ) ) {
			if ( strpos( strval( $phone_prefix[1] ) , strval( $country_code ) ) === false ) {
				$mobile = $country_code . ltrim( $mobile, '0' );
			}
		}
		
		$mobile = '+'.$mobile;
		return $mobile;
	}
	
	/**
	* @New order notification to admin user.
	*
	* @since    1.0.0
	* @access   public
	* @var      string    $order_id    
	* @var      mixed     $order    
	*/
	public function new_order_notification( $order_id, $order = false ){
		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}		
		$placeholders = $this->placeholders($order);
		$message = get_option('xc_woo_twilio_neworder_message', '');
		if(!empty($message)){
			$mobile = get_option('xc_woo_twilio_admin_number', '');
			$status = $this->send_message($message, $placeholders, $mobile);	
			$this->add_order_note($order, $status);
		}	
	}
	
	public function notification_after_order( $order_id, $old_status, $new_status, $order ){
		if( !$order_id ) {
            return;
        }
		if(empty($new_status)) return;
		
		$statuses = wc_get_order_statuses();
		$status   = 'wc-' === substr( $new_status, 0, 3 ) ? substr( $new_status, 3 ) : $new_status;
		$status   = isset( $statuses[ 'wc-' . $status ] ) ? $statuses[ 'wc-' . $status ] : $status;				
		
		
		if('yes' == get_option("xc_woo_twilio_admin_enable_order_status_{$new_status}",'')){
			$message = 	get_option("xc_woo_twilio_admin_message_{$new_status}",'');
			if(!empty($message)){
				$mobile = get_option('xc_woo_twilio_admin_number', '');
				$placeholders = $this->placeholders($order);
				$placeholders['{order_status}'] = $status;
				$status = $this->send_message($message, $placeholders, $mobile);	
				$this->add_order_note($order, $status);
			}
		}
		
		if('yes' == get_option("xc_woo_twilio_customer_enable_order_status_{$new_status}",'')){
			$message = 	get_option("xc_woo_twilio_customer_message_{$new_status}",'');
			if(!empty($message)){
				$mobile = $this->get_customer_mobile($order);
				$placeholders = $this->placeholders($order);
				$placeholders['{order_status}'] = $status;
				$status = $this->send_message($message, $placeholders, $mobile);	
				$this->add_order_note($order, $status);
			}
		}
	}
	
	public function new_customer_note( $data ){
		if('yes' == get_option("xc_woo_twilio_customer_enable_order_note",'')){
			$message = get_option("xc_woo_twilio_customer_message_note",'');
			$order_id					= $data['order_id'];
			$order						= new WC_Order( $order_id ); 
			if(!empty($message)){
				$mobile = $this->get_customer_mobile($order);
				$placeholders = $this->placeholders($order);
				$placeholders['{order_note}'] = $data['customer_note'];
				$status = $this->send_message($message, $placeholders, $mobile);	
				$this->add_order_note($order, $status);
			}
		}
	}
	
	public function add_order_note($order, $status){
		$note = esc_attr__( 'Message : ', 'xc-woo-twilio' );
		$note.=$status['message'];

		if($status['status'] == 'success'){
			$note .= '<br>'.esc_attr__( 'Status : Success ', 'xc-woo-twilio' );	
		}else{
			$note .= '<br>'.esc_attr__( 'Status : Faild ', 'xc-woo-twilio' );		
			$note .= '<br>'.esc_attr__( 'Error : ', 'xc-woo-twilio' );		
			$note .= $status['error'];
		}
		
		$order->add_order_note( $note );
		$order->save();
	}
	
	public function custom_order_message( $order_id, $message ){
		$order						= new WC_Order( $order_id ); 
		$note = '';
		if(!empty($message)){
			$order_status  = $order->get_status();
			$mobile = $this->get_customer_mobile($order);
			$placeholders = $this->placeholders($order);
			$placeholders['{order_status}'] = $order_status;
			$placeholders['{order_note}'] = $data['customer_note'];
			$status = $this->send_message($message, $placeholders, $mobile);
			$this->add_order_note($order, $status);
			if($status['status'] == 'success'){
				$note = esc_attr__( 'Message Sent : ', 'xc-woo-twilio' );	
				$note.=$status['message'];
			}else{
				$note = esc_attr__( 'Message Faild, Error : ', 'xc-woo-twilio' );	
				$note.=$status['error'];
			}			
		}
		echo $note;
	}
	
}