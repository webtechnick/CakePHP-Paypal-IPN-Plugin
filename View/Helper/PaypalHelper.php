<?php
/**
 * Paypal Helper part of the PayPal IPN plugin.
 *
 * @author Nick Baker
 * @link http://www.webtechnick.com
 * @license MIT
 */
App::uses('AppHelper','View/Helper');
class PaypalHelper extends AppHelper {

	var $helpers = array('Html', 'Form');

	var $config = array();

	var $encryption = array();

/**
 * Setup the config based on either the Configure::read('debug') values
 * or the PaypalIpnConfig in config/paypal_ipn_config.php
 *
 * Will attempt to read configuration in the following order:
 *   Configure::read('PaypalIpn')
 *   App::import() of config/paypal_ipn_config.php
 *   App::import() of plugin's config/paypal_ipn_config.php
 */
	function __construct(View $View, $settings = array()) {
		$this->config = Configure::read('PaypalIpn');

		if (empty($this->config)) {
			$importConfig = array(
				'type' => 'File',
				'name' => 'PaypalIpn.PaypalIpnConfig',
				'file' => APP . 'Config' . DS . 'paypal_ipn_config.php'
			);
			if (!class_exists('PaypalIpnConfig')) {
				App::import($importConfig);
			}
			if (!class_exists('PaypalIpnConfig')) {
				// Import from paypal plugin configuration
				$importConfig['file'] = 'Config' . DS . 'paypal_ipn_config.php';
				App::import($importConfig);
			}
			if (!class_exists('PaypalIpnConfig')) {
				trigger_error(__d('paypal_ipn', 'PaypalIpnConfig: The configuration could not be loaded.'), E_USER_ERROR);
			}

			$config = new PaypalIpnConfig();

			$vars = get_object_vars($config);
			foreach ($vars as $property => $configuration) {
				if (strpos($property, 'encryption_') === 0) {
					$name = substr($property, 11);
					$this->encryption[$name] = $configuration;
				} else {
					$this->config[$property] = $configuration;
				}
			}
		}
		parent::__construct($View, $settings);
	}

/**
 * Creates a complete form button to Pay Now, Donate, 
 * Add to Cart, or Subscribe using the paypal service.
 * Configuration for the button is in /config/paypal_ip_config.php
 *
 * for this to work the option 'item_name' and 'amount' must be set in the array options or default config options.
 *
 * Example:
 *  $paypal->button('Pay Now', array('amount' => '12.00', 'item_name' => 'test item'));
 *  $paypal->button('Subscribe', array('type' => 'subscribe', 'amount' => '60.00', 'term' => 'month', 'period' => '2'));
 *  $paypal->button('Donate', array('type' => 'donate', 'amount' => '60.00'));
 *  $paypal->button('Add To Cart', array('type' => 'addtocart', 'amount' => '15.00'));
 *  $paypal->button('View Cart', array('type' => 'viewcart'));
 *  $paypal->button('Unsubscribe', array('type' => 'unsubscribe'));
 *  $paypal->button('Checkout', array(
 *      'type' => 'cart',
 *      'items' => array(
 *           array('item_name' => 'Item 1', 'amount' => '120', 'quantity' => 2, 'item_number' => '1234'),
 *           array('item_name' => 'Item 2', 'amount' => '50'),
 *           array('item_name' => 'Item 3', 'amount' => '80', 'quantity' => 3),
 *       )
 *  ));
 *
 * Test Example:
 *  $paypal->button('Pay Now', array('test' => true, 'amount' => '12.00', 'item_name' => 'test item'));
 *
 * @access public
 * @param String $title takes the title of the paypal button (default "Pay Now" or "Subscribe" depending on option['type'])
 * @param Array $options takes an options array defaults to (configuration in /config/paypal_ipn_config.php)
 *        test: true|false switches default settings in /config/paypal_ipn_config.php between settings and testSettings
 *        type: 'paynow', 'addtocart', 'donate', 'unsubscribe', 'cart', or 'subscribe' (default 'paynow')
 *
 * You may pass in api name value pairs to be passed directly to the paypal
 * form link. Refer to paypal.com for a complete list. Some Paypal API examples:
 *   float amount      - value
 *   string notify_url - url
 *   string item_name  - name of product.
 */
	function button($title, $options = array(), $buttonOptions = array()) {
		if (is_array($title)) {
			$buttonOptions = $options;
			$options = $title;
		} else if (empty($buttonOptions['label'])) {
			$buttonOptions['label'] = $title;
		}

		$encryption = false;
		if (!empty($options['test'])) {
			if ($options['test'] === true) {
				$defaults = $this->config['test'];
				$encryption = 'test';
			} elseif (is_array($options['test'])) {
				$defaults = $options['test'];
				if (isset($options['_encryption'])) {
					$encryption = $options['_encryption'];
					unset($options['_encryption']);
				}
			} else {
				$defaults = $this->config[$options['test']];
				$encryption = $options['test'];
			}
		} else {
			$defaults = $this->config['default'];
			$encryption = 'default';
		}

		$options = array_merge($defaults, $options);
		$options['type'] = (isset($options['type'])) ? $options['type'] : "paynow";

		switch ($options['type']) {
			case 'subscribe':   // Subscribe
				$options['cmd'] = '_xclick-subscriptions';
				$default_title = 'Subscribe';
				$options['no_note'] = 1;
				$options['no_shipping'] = 1;
				$options['src'] = 1;
				$options['sra'] = 1;
				$options = $this->__subscriptionOptions($options);
				break;
			case 'addtocart':   // Add To Cart
				$options['cmd'] = '_cart';
				$options['add'] = '1';
				$default_title = 'Add To Cart';
				break;
			case 'viewcart':    // View Cart
				$options['cmd'] = '_cart';
				$options['display'] = '1';
				$default_title = 'View Cart';
				break;
			case 'donate':      // Doante
				$options['cmd'] = '_donations';
				$default_title = 'Donate';
				break;
			case 'unsubscribe': //Unsubscribe
				$options['cmd'] = '_subscr-find';
				$options['alias'] = $options['business'];
				$default_title = 'Unsubscribe';
				break;
			case 'cart':        // upload cart
				$options['cmd'] = '_cart';
				$options['upload'] = 1;
				$default_title = 'Checkout';
				$options = $this->__uploadCartOptions($options);
				break;
			default:            // Pay Now
				$options['cmd'] = '_xclick';
				$default_title = 'Pay Now';
				break;
		}

		if (empty($buttonOptions['label'])) {
			if (empty($options['label'])) {
				$buttonOptions['label'] = $default_title;
			} else {
				$buttonOptions['label'] = $options['label'];
			}
		}
		$retval = "<form action='{$options['server']}/cgi-bin/webscr' method='post'><div class='paypal-form'>";
		unset($options['server']);

		$encryptedFields = false;
		if (!empty($options['encrypt']) && $encryption) {
			if (is_string($encryption) && isset($this->encryption[$encryption])) {
				$encryption = $this->encryption[$encryption];
			}

			if (is_array($encryption)) {
				$encryptedFields = $this->__encryptFields($options, $encryption);
			}
		}

		if ($encryptedFields === false) {
			foreach ($options as $name => $value) {
				$retval .= $this->__hiddenNameValue($name, $value);
			}
		} else {
			$retval .= $encryptedFields;
		}

		$retval .= $this->__submitButton($buttonOptions);

		return $retval;
	}

/**
 * Constructs the name value pair in a hidden input html tag
 *
 * @param array hold key/value options of paypal button.
 * @return String hidden encrypted fields
 */
	function __encryptFields($options, $encryption) {
		if (!file_exists($encryption['key_file'])) {
			$this->log("ERROR: MY_KEY_FILE {$encryption['key_file']} not found\n");
			return false;
		}
		if (!file_exists($encryption['cert_file'])) {
			$this->log("ERROR: MY_CERT_FILE {$encryption['cert_file']} not found\n");
			return false;
		}
		if (!file_exists($encryption['paypal_cert_file'])) {
			$this->log("ERROR: PAYPAL_CERT_FILE {$encryption['paypal_cert_file']} not found\n");
			return false;
		}

		$options['cert_id'] = $encryption['cert_id'];

		// Assign Build Notation for PayPal Support
		$options['bn'] = $encryption['bn'];

		$data = '';
		foreach ($options as $key => $value) {
			if ($value != '') {
				$data .= "{$key}={$value}\n";
			}
		}

		$openssl_cmd   = array();
		$openssl_cmd[] = "({$encryption['openssl']} smime";
		$openssl_cmd[] = "-sign -signer {$encryption['cert_file']}";
		$openssl_cmd[] = "-inkey {$encryption['key_file']}";
		$openssl_cmd[] = "-outform der -nodetach -binary <<_EOF_\n{$data}\n_EOF_\n) |";
		$openssl_cmd[] = "{$encryption['openssl']} smime -encrypt";
		$openssl_cmd[] = "-des3 -binary -outform pem {$encryption['paypal_cert_file']}";
		$openssl_cmd   = implode(' ', $openssl_cmd);

		exec($openssl_cmd, $output, $error);
		if ($error) {
			return false;
		}

		$encryptedFields = implode("\n", $output);
		return implode(' ', array(
			'<input type="hidden" name="cmd" value="_s-xclick">',
			"<input type='hidden' name='encrypted' value='{$encryptedFields}' />"
		));
	}

/**
 * Constructs the name value pair in a hidden input html tag
 *
 * @param string name is the name of the hidden html element.
 * @param string value is the value of the hidden html element.
 * @return string hidden html field
 */
	function __hiddenNameValue($name, $value){
		return "<input type='hidden' name='{$name}' value='{$value}' />";
	}

/**
 * Constructs the submit button from the provided text
 *
 * @param string text | text is the label of the submit button.	Can use plain text or image url.
 * @return string html form button and close form
 */
	function __submitButton($options = array()) {
		$options = is_array($options) ? $options : array('label' => $options);
		return "</div>" . $this->Form->end($options);
	}

/**
 * Converts human readable subscription terms into paypal terms if need be
 *
 * @param array options | human readable options into paypal API options
 *              int    period - paypal api period of term, 2, 3, 1
 *              string term   - paypal API term //month, year, day, week
 *              float  amount - paypal API amount to charge for perioud of term.
 * @return array options
 */
	function __subscriptionOptions($options = array()) {
		// Period... every 1, 2, 3, etc.. Term
		if (isset($options['period'])) {
			$options['p3'] = $options['period'];
			unset($options['period']);
		}
		// Mount billed
		if (isset($options['amount'])) {
			$options['a3'] = $options['amount'];
			unset($options['amount']);
		}
		// Terms, Month(s), Day(s), Week(s), Year(s)
		if (isset($options['term'])) {
			switch ($options['term']) {
				case 'month': $options['t3'] = 'M'; break;
				case 'year':  $options['t3'] = 'Y'; break;
				case 'day':   $options['t3'] = 'D'; break;
				case 'week':  $options['t3'] = 'W'; break;
				default:      $options['t3'] = $options['term'];
			}
			unset($options['term']);
		}

		return $options;
	}

/**
 * Converts an array of items into paypal friendly name/value pairs
 *
 * @param array of options that will be returned with proper paypal friendly name/value pairs for items
 * @return array options
 */
	function __uploadCartOptions($options = array()) {
		if (isset($options['items']) && is_array($options['items'])) {
			$count = 1;
			foreach ($options['items'] as $item) {
				foreach ($item as $key => $value) {
					$options[$key.'_'.$count] = $value;
				}
				$count++;
			}
			unset($options['items']);
		}
		return $options;
	}

}