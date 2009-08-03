<?php
App::import('Core', array('Xml', 'HttpSocket'));

class PaypalSource extends DataSource {
  var $Http = null;
  
  function __construct($config){
    $this->Http =& new HttpSocket();
  }
  
  function isValid($data){
    $this->log('Im in isValid','paypal');
    $transaction = 'cmd=_notify-validate';
    foreach($data as $key => $value){
      $value = urlencode(stripslashes($value));
      $transaction .= "&$key=$value";
    }
  
    if(isset($data['test_ipn'])) {
      $server = 'https://www.sandbox.paypal.com';
    } else {
      $server = 'https://www.paypal.com';
    }
    
    $response = $this->Http->post($server, $transaction);
    
    if(!$response){
      $this->log('Bad Response: ' . $response, 'paypal');
      return false;
    }
    
    $this->log($response, 'paypal'); 
    debug($response);
  }
}

?>
