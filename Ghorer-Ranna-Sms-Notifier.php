<?php

/**
 * Plugin Name: Ghorer Ranna Sms Notifier
 * Plugin URI: https://github.com/Pronoy2007/Ghorer-Ranna-Sms-Notifier
 * Author: Ghorer Ranna
 * Author URI: https://github.com/Pronoy2007/Ghorer-Ranna-Sms-Notifier
 * Description: Send SMS Notifications from Woocommerce Order Notes and Order Status Changes.
 * Version: 0.0.1
 */
 
 // Event of a new email for the order - change in status
 
add_action( 'woocommerce_order_status_change','Pronoy2007_send_sms_on_new_order_status', 10, 4);

// Event of the order note
add_action( 'woocommerce_new_customer_note_notification','Pronoy2007_send_sms_on_new_order_note' );

function Pronoy2007_send_sms_on_new_order_status($order_id , $old_status , $new_status , $order ) {
    // Get the order Object
    $my_order = wc_get_order( $order_id);
    
    // firstname
    $firstname = $my_order->get_billing_first_name();
    
    // Phone
    $phone = $my_order->get_billing_phone();
    
    $shopname = get_option( 'woocommerce_email_from_name');
    
    $default_sms_message = "Thank you $firstname for shopping from $shopname. Your order $order_id is $new_status";
    
    Pronoy2007_send_sms_to_customer( $phone, $default_sms_message, $shopname);
    
    
}
function Pronoy2007_send_sms_on_new_order_note($email_args){
    
    // Pronoy2007_send_sms_to_customer();
    
}



function Pronoy2007_send_sms_to_customer($phone = 'NULL', $default_sms_message, $shopname){
    $url = "https://sms.to/app#/sms/send";
    if(NULL==$phone) {
        return;
        
        $msgdata = array(
		'method' => 'SendSms',
		'userdata' => array(
			'username' => 'Liz',
			'password' => 'Plugin',
		),
		'msgdata' => array(
			array(
				'number' => $phone,
				'message' => $default_sms_message,
				'senderid' => $shopname,
			)
		)
	);
    
        
	$url = 'http://sms.sukumasms.com/api/v1/json/';

	$arguments = array(
		'method' => 'POST',
		'body' => json_encode( $msgdata ),
	);

	$response = wp_remote_post( $url, $arguments );
	
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
	}
     
 }
        
} 
    
}
