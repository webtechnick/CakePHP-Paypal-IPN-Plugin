<?php
class InstantPaymentNotification extends PaypalIpnAppModel {
    /**
     * name is the name of the model
     * 
     * @var $name string
     * @access public
     */
    var $name = 'InstantPaymentNotification';
    
    /*******************
      * the PaypalSource
      */
    var $paypal = null;
    
    /************************
      * verifies POST data given by the paypal instant payment notification
      * @param array $data Most likely directly $_POST given by the controller.
      * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
      */
    function isValid($data){
      if(!empty($data)){
        App::import(array('type' => 'File', 'name' => 'PaypalIpn.PaypalIpnSource', 'file' => 'models'.DS.'datasources'.DS.'paypal_ipn_source.php'));
        $this->paypal = new PaypalIpnSource();
        return $this->paypal->isValid($data);
      }
    }
}
?>