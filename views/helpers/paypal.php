<?php
class PaypalHelper extends AppHelper {
  
  var $helpers = array('Html','Form');

  /*********************
    *  Setup the config based on the paypal_ipn_config in /plugins/paypal_ipn/config/paypal_ipn_config.php
    */
  function __construct(){
    App::import(array('type' => 'File', 'name' => 'PaypalIpn.PaypalIpnConfig', 'file' => 'config'.DS.'paypal_ipn_config.php'));
    $this->config =& new PaypalIpnConfig();
    parent::__construct();  
  }
  
  /********************
    *  function button will create a complete form button to Pay Now, Donate, Add to Cart, or Subscribe using the paypal service.
    *  Configuration for the button is in /config/paypal_ip_config.php
    *  
    * for this to work the option 'item_name' and 'amount' must be set in the array options.
    *
    *  Example: 
    *     $paypal->button('Pay Now', array('amount' => '12.00', 'item_name' => 'test item'));
    *     $paypal->button('Subscribe', array('type' => 'subscribe', 'amount' => '60.00', 'term' => 'month', 'period' => '2'));
    *     $paypal->button('Donate', array('type' => 'donate', 'amount' => '60.00'));
    *     $paypal->button('Add To Cart', array('type' => 'addtocart', 'amount' => '15.00'));
    *  Test Example:
    *     $paypal->button('Pay Now', array('test' => true, 'amount' => '12.00', 'item_name' => 'test item'));
    *
    * @access public
    * @param String $title takes the title of the paypal button (default "Pay Now" or "Subscribe" depending on option['type'])
    * @param Array $options takes an options array defaults to (configuration in /config/paypal_ipn_config.php)
    * 
    *   helper_options:  
    *      test: true|false switches default settings in /config/paypal_ipn_config.php between settings and testSettings
    *      type: 'paynow' or 'subscribe' (default 'paynow')
    *    
    *    You may pass in api name value pairs to be passed directly to the paypal form link.  Refer to paypal.com for a complete list.
    *    some paypal API examples: 
    *      amount: float value
    *      notify_url: string url
    *      item_name: string name of product.
    *      etc...
    */
  function button($title = null, $options = array()){
    if(is_array($title)){
      $options = $title;
      $title = isset($options['label']) ? $options['label'] : null;
    }    
    $defaults = (isset($options['test']) && $options['test']) ? $this->config->testSettings : $this->config->settings; 
    $options = array_merge($defaults, $options);
    
    switch($options['type']){
      case 'subscribe': //Subscribe
        $options['cmd'] = '_xclick-subscriptions';
        $default_title = 'Subscribe';
        $options = $this->__subscriptionOptions($options);
        break;
      case 'addtocart': //Add To Cart
        $options['cmd'] = '_cart';
        $options['add'] = '1';
        $default_title = 'Add To Cart';
        break;
      case 'donate': //Doante
        $options['cmd'] = '_donations';
        $default_title = 'Donate';
        break;
      default: //Pay Now
        $options['cmd'] = '_xclick';
        $default_title = 'Pay Now';
        break;
    }
    
    $title = (empty($title)) ? $default_title : $title;
    $retval = "<form action='{$options['server']}/cgi-bin/webscr' method='post'>";
    unset($options['server']);
    foreach($options as $name => $value){
       $retval .= $this->__hiddenNameValue($name, $value);
    }
    $retval .= $this->__submitButton($title);
    
    return $retval;
  }
  
  /****
   *  __hiddenNameValue constructs the name value pair in a hidden input html tag
   * @access private
   */
  function __hiddenNameValue($name, $value){
    return "<input type='hidden' name='$name' value='$value' />";
  }
  
  /****
   *  __submitButton constructs the submit button from the provided text
   * @access private
   */
  function __submitButton($text){
    return $this->Form->end(array('label' => $text, 'div' => false));
  }
  
  /*************
    * __subscriptionOptions conversts human readable subscription terms 
    * into paypal terms if need be
    */
  function __subscriptionOptions($options = array()){
    //Period... every 1, 2, 3, etc.. Term
    if(isset($options['period'])) $options['p3'] = $options['period'];
    //Mount billed
    if(isset($options['amount'])) $options['a3'] = $options['amount'];
    //Terms, Month(s), Day(s), Week(s), Year(s)
    if(isset($options['term'])){
      switch($options['term']){
        case 'month': $options['t3'] = 'M'; break;
        case 'year': $options['t3'] = 'Y'; break;
        case 'day': $options['t3'] = 'D'; break;
        case 'week': $options['t3'] = 'W'; break;
        default: $options['t3'] = $options['term'];
      }
      
    }//$options['a3'] = $options['amount'];
    
    return $options;
  }
}
?>