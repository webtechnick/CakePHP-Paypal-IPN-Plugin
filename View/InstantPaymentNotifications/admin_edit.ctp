<div class="instantPaymentNotifications form">
<h1>Add/Edit Instant Payment Notification</h1>
<?php echo $this->Form->create('InstantPaymentNotification');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('notify_version');
		echo $this->Form->input('verify_sign');
		echo $this->Form->input('test_ipn');
		echo $this->Form->input('address_city');
		echo $this->Form->input('address_country');
		echo $this->Form->input('address_country_code');
		echo $this->Form->input('address_name');
		echo $this->Form->input('address_state');
		echo $this->Form->input('address_status');
		echo $this->Form->input('address_street');
		echo $this->Form->input('address_zip');
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('payer_business_name');
		echo $this->Form->input('payer_email');
		echo $this->Form->input('payer_id');
		echo $this->Form->input('payer_status');
		echo $this->Form->input('contact_phone');
		echo $this->Form->input('residence_country');
		echo $this->Form->input('business');
		echo $this->Form->input('item_name');
		echo $this->Form->input('item_number');
		echo $this->Form->input('quantity');
		echo $this->Form->input('receiver_email');
		echo $this->Form->input('receiver_id');
		echo $this->Form->input('custom');
		echo $this->Form->input('invoice');
		echo $this->Form->input('memo');
		echo $this->Form->input('option_name_1');
		echo $this->Form->input('option_name_2');
		echo $this->Form->input('option_selection1');
		echo $this->Form->input('option_selection2');
		echo $this->Form->input('tax');
		echo $this->Form->input('auth_id');
		echo $this->Form->input('auth_exp');
		echo $this->Form->input('auth_amount');
		echo $this->Form->input('auth_status');
		echo $this->Form->input('num_cart_items');
		echo $this->Form->input('parent_txn_id');
		echo $this->Form->input('payment_date');
		echo $this->Form->input('payment_status');
		echo $this->Form->input('payment_type');
		echo $this->Form->input('pending_reason');
		echo $this->Form->input('reason_code');
		echo $this->Form->input('remaining_settle');
		echo $this->Form->input('shipping_method');
		echo $this->Form->input('shipping');
		echo $this->Form->input('transaction_entity');
		echo $this->Form->input('txn_id');
		echo $this->Form->input('txn_type');
		echo $this->Form->input('exchange_rate');
		echo $this->Form->input('mc_currency');
		echo $this->Form->input('mc_fee');
		echo $this->Form->input('mc_gross');
		echo $this->Form->input('mc_handling');
		echo $this->Form->input('mc_shipping');
		echo $this->Form->input('payment_fee');
		echo $this->Form->input('payment_gross');
		echo $this->Form->input('settle_amount');
		echo $this->Form->input('settle_currency');
		echo $this->Form->input('auction_buyer_id');
		echo $this->Form->input('auction_closing_date');
		echo $this->Form->input('auction_multi_item');
		echo $this->Form->input('for_auction');
		echo $this->Form->input('subscr_date');
		echo $this->Form->input('subscr_effective');
		echo $this->Form->input('period1');
		echo $this->Form->input('period2');
		echo $this->Form->input('period3');
		echo $this->Form->input('amount1');
		echo $this->Form->input('amount2');
		echo $this->Form->input('amount3');
		echo $this->Form->input('mc_amount1');
		echo $this->Form->input('mc_amount2');
		echo $this->Form->input('mc_amount3');
		echo $this->Form->input('recurring');
		echo $this->Form->input('reattempt');
		echo $this->Form->input('retry_at');
		echo $this->Form->input('recur_times');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('subscr_id');
		echo $this->Form->input('case_id');
		echo $this->Form->input('case_type');
		echo $this->Form->input('case_creation_date');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List InstantPaymentNotifications'), array('action' => 'index'));?></li>
	</ul>
</div>
