(function( $ ) {
	'use strict';
	jQuery(document).ready(function($) {
        if($('#xc_woo_twilio_order_meta_box').length > 0){
			$(document).on('click', '.xc_woo_twilio_order_message_buttons_load_message', function(){
				$( '#xc_woo_twilio_order_meta_box' ).block({ message: null, overlayCSS: { background: '#fff',opacity: 0.6 } });
				var $order_id = $(this).data('order-id');
				$.post(xc_woo_twilio.ajax_url, {'action':"xc_woo_twilio_order_message_load_message", 'order_id':$order_id}, function($data){
					$('#xc_woo_twilio_order_message').val($data.data.message);
					$( '#xc_woo_twilio_order_meta_box' ).unblock();
				},"JSON");
			});
			
			$(document).on('click', '.xc_woo_twilio_order_message_buttons_send_message', function(){
				$( '#xc_woo_twilio_order_meta_box' ).block({ message: null, overlayCSS: { background: '#fff',opacity: 0.6 } });
				var $order_id = $(this).data('order-id');
				var $message = $('#xc_woo_twilio_order_message').val();
				$.post(xc_woo_twilio.ajax_url, {'action':"xc_woo_twilio_order_message_send_message", 'order_id':$order_id, 'message' : $message}, function($data){
					$('#xc_woo_twilio_order_message').val('');
					alert($data.data.message);
					$( '#xc_woo_twilio_order_meta_box' ).unblock();
				},"JSON");
			});
				
		}
    });
})( jQuery );
