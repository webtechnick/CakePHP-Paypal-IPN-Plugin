<?php
/************
  * Use these settings to set defaults for the Paypal Helper class.
  * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
  * 
  * All these options can be set on the fly as well within the helper
  */
  
  define('LIVE_PAYPAL', Configure::read('PB.paypal'));
  define('TEST_PAYPAL', Configure::read('PB.test_paypal'));
  define('NOTIFY_URL', Configure::read('PB.paypal_ipn'));

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
  var $settings = array(
    'business' => LIVE_PAYPAL, //Your Paypal email account
    'server' => 'https://www.paypal.com', //Main paypal server.
    'notify_url' => NOTIFY_URL, //Notify_url... set this to the process path of your paypal_ipn::instant_payment_notification::process action
    'currency_code' => 'USD', //Currency
    'lc' => 'US', //Locality
    'item_name' => 'Paypal_IPN', //Default item name.
    'amount' => '15.00' //Default item amount.
  );
  
  /***********
    * Test settings to test with using a sandbox paypal account.
    */
  var $testSettings = array(
    'business' => TEST_PAYPAL,
    'server' => 'https://www.sandbox.paypal.com',
    'notify_url' => NOTIFY_URL,
    'currency_code' => 'USD',
    'lc' => 'US',
    'item_name' => 'Paypal_IPN',
    'amount' => '15.00'
  );

}
?>
