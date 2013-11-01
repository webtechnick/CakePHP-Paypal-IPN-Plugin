<div class="paypalItems view">
<h1><?php echo __('PaypalItem');?></h1>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Instant Payment Notification Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($paypalItem['PaypalItem']['instant_payment_notification_id'], array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'view', 'id' => $paypalItem['PaypalItem']['instant_payment_notification_id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Item Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['item_name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Item Number'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['item_number']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Quantity'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['quantity']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Mc Gross'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['mc_gross']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Mc Shipping'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['mc_shipping']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Mc Handling'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['mc_handling']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Tax'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['tax']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypalItem['PaypalItem']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit PaypalItem'), array('action' => 'edit', $paypalItem['PaypalItem']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete PaypalItem'), array('action' => 'delete', $paypalItem['PaypalItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $paypalItem['PaypalItem']['id'])); ?> </li>
		<li><?php echo $html->link(__('List PaypalItems'), array('action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New PaypalItem'), array('action' => 'add')); ?> </li>
	</ul>
</div>
