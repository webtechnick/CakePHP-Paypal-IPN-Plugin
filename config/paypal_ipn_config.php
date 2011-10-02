<?php
/************
 * Use these settings to set defaults for the Paypal Helper class.
 * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
 *
 * All these options can be set on the fly as well within the helper
 */
class PaypalIpnConfig {

/************
 * Each settings key coresponds to the Paypal API.  Review www.paypal.com for more.
 */
	var $default = array(
		'business'      => 'live_email@paypal.com',         // 'live_email@paypal.com', //Your Paypal email account
		'server'        => 'https://www.paypal.com',        // Main paypal server.
		'notify_url'    => 'http://yoursite.com/paypal_ipn/process',
                                                            // 'http://yoursite.com/paypal_ipn/process',
                                                            // Notify_url... set this to the process path of your
                                                            // paypal_ipn::instant_payment_notification::process action
		'currency_code' => 'USD',                           // Currency
		'lc'            => 'US',                            // Locality
		'item_name'     => 'Paypal_IPN',                    // Default item name.
		'amount'        => '15.00',                         // Default item amount.
		'encrypt'       => false,                           // Set to true to enable encryption
	);

/***********
 * Test settings to test with using a sandbox paypal account.
 */
	  var $test = array(
		'business'      => 'sandbox_email@paypal.com',         // 'live_email@paypal.com', //Your Paypal email account
		'server'        => 'https://www.sandbox.paypal.com',        // Main paypal server.
		'notify_url'    => 'http://test.yoursite.com/paypal_ipn/process',
                                                            // 'http://test.yoursite.com/paypal_ipn/process',
                                                            // Notify_url... set this to the process path of your
                                                            // paypal_ipn::instant_payment_notification::process action
		'currency_code' => 'USD',                           // Currency
		'lc'            => 'US',                            // Locality
		'item_name'     => 'Paypal_IPN',                    // Default item name.
		'amount'        => '15.00',                         // Default item amount.
		'encrypt'       => false,                           // Set to true to enable encryption
	);

	var $encryption_default = array(
		'cert_id'       => '',                              // Certificate ID (gotten after certificate uploaded to paypal)
		'key_file'      => '',                              // Absolute path to Private Key File
		'cert_file'     => '',                              // Absolute path to Public Certificate file
		'paypal_cert_file' => '',                           // Absolute path to Paypal certificate file
		'openssl'       => '/usr/bin/openssl',              // OpenSSL location
		'bn'            => 'cakephp_paypal-ipn-plugin',     // Build Notation
	);

	var $encryption_test = array(
		'cert_id'       => '',                              // Certificate ID (gotten after certificate uploaded to paypal)
		'key_file'      => '',                              // Absolute path to Private Key File
		'cert_file'     => '',                              // Absolute path to Public Certificate file
		'paypal_cert_file' => '',                           // Absolute path to Paypal certificate file
		'openssl'       => '/usr/bin/openssl',              // OpenSSL location
		'bn'            => 'cakephp_paypal-ipn-plugin',     // Build Notation
	);

}