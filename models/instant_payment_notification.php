<?php
class InstantPaymentNotification extends PaypalIpnAppModel {
    /**
     * name is the name of the model
     * 
     * @var $name string
     * @access public
     */
    var $name = 'InstantPaymentNotification';
    
    /************************
      * verifies POST data given by the paypal instant payment notification
      * @param array $data Most likely directly $_POST given by the controller.
      * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
      */
    function verify($data){
      if(!empty($data)){
        //start the URL for verification.
        $transaction = 'cmd=_notify-validate';
        foreach($data as $key => $value){
          $value = urlencode(stripslashes($value));
          $transaction .= "&$key=$value";
        }
        
        //create headers for post back
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($transaction) . "\r\n\r\n";
        
        //If this is a sandbox transaction then 'test_ipn' will be set to '1'
        if(isset($data['test_ipn'])) {
          $server = 'www.sandbox.paypal.com';
        } else {
          $server = 'www.paypal.com';
        }
        
        //and post the transaction back for validation
        $fp = fsockopen ("ssl://$server", 443, $errno, $errstr, 30);
        if (!$fp) {
          //...didn't get a response so log error in error logs
          $this->log('HTTP Error in InstantPaymentNotifications::process while posting back to PayPal: Transaction='.$transaction);
        }
        else {
          //...got a response, so we'll through the response looking for VERIFIED or INVALID
          fputs($fp, $header . $transaction);
          while (!feof($fp)) {
            $response = fgets ($fp, 1024);
            if (strcmp($response, "VERIFIED") == 0) {
              return true; //Its verified.
            }
            elseif(strcmp ($response, "INVALID") == 0) {
              //The response is INVALID so log it for investigation
              $this->log("Found Invalid: $transaction",'paypal');
            }
          }
        }
        fclose ($fp);
      }
      
      return false;
    }
}
?>