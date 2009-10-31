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
      return false;
    }
    
    /****
      * Utility method to send basic emails based on a paypal IPN transaction.
      * This method is very basic, if you need something more complicated I suggest
      * creating your own method in the afterPaypalNotification function you build
      * in the app_controller.php
      *
      * @param array $options of the ipn to send 
      */
    function email($options = array()){
      if(isset($options['id'])) $this-> id = $options['id'];
      $this->read();
      $defaults = array(
        'subject' => 'Thank you for your payment',
        'sendAs' => 'html',
        'to' => $this->data['InstantPaymentNotification']['payer_email'],
        'from' => $this->data['InstantPaymentNotification']['business'],
        'cc' => array(),
        'bcc' => array(),
        'layout' => 'default',
        'template' => 'paypal_ipn_email',
        'log' => true,
        'message' => null 
      );
      $options = array_merge($defaults, $options);
      
      print_r($options);
      if($options['log']){
        $this->log("Emailing: {$options['to']} through the PayPal IPN Plugin. ",'email');
      }
      
      App::import('Component','Email');
      $Email = new EmailComponent;
      
      App::import('Controller','PaypalIpn.InstantPaymentNotificationsController');
      $IPN = new InstantPaymentNotificationsController;
      
      print_r($IPN);
      
      $Email->to = $options['to'];
      $Email->from = $options['from'];
      $Email->bcc = $options['bcc'];
      $Email->cc = $options['cc'];
      $Email->subject = $options['subject'];
      $Email->sendAs = $options['sendAs'];
      $Email->template = $options['template'];
      $Email->layout = $options['layout'];
      
      ($options['message']) ? $Email->send($options['message']) : $Email->send();
    }
}
?>