<?php

App::import('Core', array('HttpSocket'));

class InstantPaymentNotification extends PaypalIpnAppModel {
    /**
     * name is the name of the model
     * 
     * @var $name string
     * @access public
     */
    var $name = 'InstantPaymentNotification';
    
    /*******************
      * the HttpSocket.
      */
    var $Http = null;
    
    /************************
      * verifies POST data given by the paypal instant payment notification
      * @param array $data Most likely directly $_POST given by the controller.
      * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
      */
    function verify($data){
      if(!empty($data)){
        $this->Http =& new HttpSocket();
        
        $data['cmd'] = '_notify-validate';
      
        if(isset($data['test_ipn'])) {
          $server = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
          $server = 'https://www.paypal.com/cgi-bin/webscr';
        }
        
        $response = $this->Http->post($server, $data);
        
        if($response == "VERIFIED"){
          return true;
        }
        
        if(!$response){
          $this->log('HTTP Error in InstantPaymentNotification::process while posting back to PayPal', 'paypal');
        }
        
        return false;
      }
    }
}
?>