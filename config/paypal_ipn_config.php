<?php
/************
  * This is if you plan on using the Paypal Helper class to create your
  * Paynow and Subscribe buttons for you.
  *
  * All these options can be set on the fly as well.
  */
class PaypalIpnConfig {

  /************
    * Each settings key coresponds to the Paypal API 
    */
  var $settings = array(
    'business' => 'nurvzy@gmail.com',
    'server' => 'https://www.paypal.com',
    'notify_url' => 'http://www.webtechnick.com/paypal_ipn/process',
    'currency_code' => 'USD',
    'lc' => 'US',
    'cmd' => '_xclick',
    'item_name' => 'Paypal_IPN',
    'amount' => '15.00'
  );
  
  var $testSettings = array(
    'business' => 'nick@webtechnick.com',
    'server' => 'https://www.sandbox.paypal.com',
    'notify_url' => 'http://www.webtechnick.com/paypal_ipn/process',
    'currency_code' => 'USD',
    'lc' => 'US',
    'cmd' => '_xclick',
    'item_name' => 'Paypal_IPN',
    'amount' => '15.00'
  );

}
?>
