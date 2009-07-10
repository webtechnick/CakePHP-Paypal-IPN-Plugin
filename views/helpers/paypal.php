<?php
class PaypalHelper extends AppHelper {
  
  var $helpers = array('Html');

  /*********************
    *  Setup the config based on the paypal_ipn_config in /plugins/paypal_ipn/config/paypal_ipn_config.php
    */
  function __construct(){
    App::import(array('type' => 'File', 'name' => 'PaypalIpn.PaypalIpnConfig', 'file' => 'config'.DS.'paypal_ipn_config.php'));
    $this->config =& new PaypalIpnConfig();
    parent::__construct();  
  }
  
  /********************
    *  function button will create a complete form button to either Pay Now or Subscribe using the paypal service.
    *  Configuration for the button is in /config/paypal_ip_config.php
    *  
    * for this to work the option 'item_name' and 'amount' must be set in the array options.
    *
    *  Example: 
    *     $paypal->button('Pay Now', array('amount' => '12.00', 'item_name' => 'test item'));
    *     $paypal->button('Subscribe', array('type' => 'subscribe', 'amount' => '12.00', 'item_name' => 'test item'));
    * @access public
    * @param String $title takes the title of the paypal button (default "Pay Now" or "Subscribe" depending on option['type'])
    * @param Array $options takes an options array defaults to (configuration in /config/paypal_ipn_config.php)
    * 
    *   helper_options:  
    *      test: true|false switches default settings in /config/paypal_ipn_config.php between settings and testSettings
    *       type: 'pay' or 'subscribe' (default 'pay')
    *    
    *    You may pass in api name value pairs to be passed directly to the paypal form link.  Refer to paypal.com for a complete list.
    *    some paypal API examples: 
    *      amount: float value
    *      notify_url: string url
    *      item_name: string name of product.
    *      etc...
    */
  function button($title = null, $options = array()){
    $defaults = (isset($options['test']) && $options['test']) ? $this->config->testSettings : $this->config->settings; 
    $options = array_merge($defaults, $options);
    
    if(isset($options['type']) && $options['type'] == 'subscribe'){
      return $this->__paypalSubscribeButton($title, $options);
    }
    else {
      return $this->__paypalPayNowButton($title, $options);
    }
  }
  
  /****
   *  __paypalPayNowButton constructs the pay now form from the given options
   * @access private
   */
  function __paypalPayNowButton($title = null, $options = array()){
    $title = (empty($title)) ? "Pay Now" : $title;
    
    $retval = "<form action='{$options['server']}/cgi-bin/webscr' method='post'>";
    unset($options['server']);
    foreach($options as $name => $value){
       $retval .= $this->__hiddenNameValue($name, $value);
    }
    $retval .= $this->__submitButton($title);
    $retval .= "</form>";
    
    return $retval;
  }
  
  /****
   *  __paypalSubscribeButton constructs the subscribe form from the given options
   * @access private
   */
   function __paypalSubscribeButton($title = null, $options = array()){
    $title = (empty($title)) ? "Pay Now" : $title;
    return "SUBSCRIBE";
  }
  
  /****
   *  __hiddenNameValue constructs the name value pair in a hidden input html tag
   * @access private
   */
  function __hiddenNameValue($name, $value){
    return $this->Html->tag('input', null, array('type' => 'hidden', 'name' => $name, 'value' => $value));
  }
  
  /****
   *  __submitButton constructs the submit button from the provided text
   * @access private
   */
  function __submitButton($text){
    return $this->Html->tag('input', null, array('type' => 'submit', 'value' => $text));
  }
}
?>