<?php
App::import('Datsource', 'PayplIpn.PaypalIpnSource');
App::import('Core', 'HttpSocket');
Mock::generatePartial('HttpSocket');
class PaypalIpnTestCase extends CakeTestCase {
  var $PaypalIpn = null;
  
  function startTest(){
  	$this->PaypalIpn = new PaypalIpnSource(array());
    $this->PaypalIpn->Http = new MockHttpSocket();
  }
  
  function testIsValid(){
  	$data = array(
    	'test' => 'string'
    );
    
    $this->PaypalIpn->Http->expectOnce('post', array(
    	'https://www.paypal.com/cgi-bin/webscr',
    	array(
    		'test' => 'string',
    		'cmd' => '_notify-validate'
    	)
    ));
    $this->PaypalIpn->Http->setReturnValue('post', 'VERIFIED');
    
    $this->assertTrue($this->PaypalIpn->isValid($data));
  }
  
  function endTest(){
    unset($this->PaypalIpn);
  }
}
?>