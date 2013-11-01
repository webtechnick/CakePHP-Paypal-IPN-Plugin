<div class="instantPaymentNotifications index">
<h1><?php echo __('InstantPaymentNotifications');?></h1>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('payer_email');?></th>
	<th><?php echo $this->Paginator->sort('item_name');?></th>
	<th><?php echo $this->Paginator->sort('item_number');?></th>
	<th><?php echo $this->Paginator->sort('payment_gross');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($instantPaymentNotifications as $instantPaymentNotification):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $instantPaymentNotification['InstantPaymentNotification']['payer_email']; ?>
		</td>
		<td>
			<?php echo $instantPaymentNotification['InstantPaymentNotification']['item_name']; ?>
		</td>
		<td>
			<?php echo $instantPaymentNotification['InstantPaymentNotification']['item_number']; ?>
		</td>
		<td>
			<?php echo $instantPaymentNotification['InstantPaymentNotification']['payment_gross']; ?>
		</td>
		<td>
			<?php echo $instantPaymentNotification['InstantPaymentNotification']['created']; ?>
		</td>
		<td class="actions">
		  <?php echo $this->Html->link('View', array('action' => 'view', $instantPaymentNotification['InstantPaymentNotification']['id'])); ?>
		  <?php echo $this->Html->link('Edit', array('action' => 'edit', $instantPaymentNotification['InstantPaymentNotification']['id'])); ?>			
		  <?php echo $this->Html->link('Delete', array('action' => 'delete', $instantPaymentNotification['InstantPaymentNotification']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $instantPaymentNotification['InstantPaymentNotification']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $this->Paginator->prev('<< '.__('previous'), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $this->Paginator->numbers();?>
	<?php echo $this->Paginator->next(__('next').' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link('New InstantPaymentNotification', array('action' => 'add')); ?></li>
	</ul>
</div>
