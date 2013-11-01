<?php
App::uses('PaypalIpnAppModel','PaypalIpn.Model');
class PaypalItem extends PaypalIpnAppModel {

	var $name = 'PaypalItem';

	var $belongsTo = array(
		'InstantPaymentNotification' => array(
			'className' => 'PaypalIpn.InstantPaymentNotification',
		),
	);

}