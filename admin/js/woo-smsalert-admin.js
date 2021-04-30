(function( $ ) {
	'use strict';
	jQuery(document).ready(function($) {
        if($('#woo_smsalert_order_meta_box').length > 0){
			$(document).on('click', '.woo_smsalert_order_message_buttons_load_message', function(){
				$( '#woo_smsalert_order_meta_box' ).block({ message: null, overlayCSS: { background: '#fff',opacity: 0.6 } });
				var $order_id = $(this).data('order-id');
				$.post(woo_smsalert.ajax_url, {'action':"woo_smsalert_order_message_load_message", 'order_id':$order_id}, function($data){
					$('#woo_smsalert_order_message').val($data.data.message);
					$( '#woo_smsalert_order_meta_box' ).unblock();
				},"JSON");
			});
			
			$(document).on('click', '.woo_smsalert_order_message_buttons_send_message', function(){
				$( '#woo_smsalert_order_meta_box' ).block({ message: null, overlayCSS: { background: '#fff',opacity: 0.6 } });
				var $order_id = $(this).data('order-id');
				var $message = $('#woo_smsalert_order_message').val();
				$.post(woo_smsalert.ajax_url, {'action':"woo_smsalert_order_message_send_message", 'order_id':$order_id, 'message' : $message}, function($data){
					$('#woo_smsalert_order_message').val('');
					alert($data.data.message);
					$( '#woo_smsalert_order_meta_box' ).unblock();
				},"JSON");
			});
				
		}
    });
})( jQuery );
