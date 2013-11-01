<?php
if (!class_exists('HttpSocket')) {
	App::import('Core', array('HttpSocket'));
}

class PaypalIpnSource extends DataSource {

/**
 * Http is the HttpSocket Object.
 * @access public
 * @var object
 */
	var $Http = null;

/**
 * constructer.  Load the HttpSocket into the Http var.
 */
	function __construct() {
		if (!PHP5) {
			$this->Http =& new HttpSocket();
		} else {
			$this->Http = new HttpSocket();
		}
	}

/**
 * Strip slashes
 * @param string value
 * @return string
 */
	static function clearSlash($value){
		return get_magic_quotes_runtime() ? stripslashes($value) : $value;
	}

/**
 * verifies POST data given by the paypal instant payment notification
 * @param array $data Most likely directly $_POST given by the controller.
 * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
 */
	function isValid($data){
		$data['cmd'] = '_notify-validate';

		$data = array_map(array('PaypalIpnSource', 'clearSlash'), $data);

		if (isset($data['test_ipn'])) {
			$server = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$server = 'https://www.paypal.com/cgi-bin/webscr';
		}

		$response = $this->Http->post($server, $data);

		if ($response == "VERIFIED") {
			return true;
		}

		if (!$response) {
			$this->log('HTTP Error in PaypalIpnSource::isValid while posting back to PayPal', 'paypal');
		}

		return false;
	}

}