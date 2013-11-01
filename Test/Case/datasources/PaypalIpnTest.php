<?php
App::import('Datsource', 'PayplIpn.PaypalIpnSource');
App::import('Core', 'HttpSocket');
class PaypalIpnTest extends CakeTestCase {
	var $PaypalIpn = null;

	function startTest() {
		Mock::generatePartial('HttpSocket', 'MockHttpSocket', array('post'));
		$this->PaypalIpn = new PaypalIpnSource(array());
		$this->PaypalIpn->Http = new MockHttpSocket();
	}

	function testIsValidShouldBeFalse() {
		$this->assertFalse($this->PaypalIpn->isValid(array()));
	}

	function testIsValid() {
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

	function endTest() {
		unset($this->PaypalIpn);
	}

}