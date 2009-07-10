Paypal IPN plugin.  (Paypal Instant Payment Notification)
Version 1.0
Author: Nick Baker (nick@webtechnick.com)
Website: http://www.webtechnick.com

Browse/Download: http://projects.webtechnick.com/paypal_ipn
SVN: https://svn2.xp-dev.com/svn/nurvzy-paypal-ipn


Install:
1) copy plugin into your /app/plugins/paypal_ipn directory
2) Add the following into your routes:

 /* Paypal IPN plugin */
  Router::connect('/paypal_ipn/edit/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'edit'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/view/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'view'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/delete/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'delete'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/add', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'edit'));
  Router::connect('/paypal_ipn/process', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'process'));
  Router::connect('/paypal_ipn', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'index'));/*
  /* End Paypal IPN plugin */
  
(optional) If you want to use the built in admin access to IPNs:
3) Make sure you're logged in as an Administrator via the Auth component.
4) Navigate to www.yoursite.com/paypal_ipn



Paypal Setup:
1) I suggest you start a sandbox account at https://developer.paypal.com
2) Enable IPN in your account.



Paypal Button:
1) Make sure to use notify_url set to "http://www.yoursite.com/paypal_ipn/process" in your paypal button.
Example:
  
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick" />
  <input type="hidden" name="business" value="myPaypalEmail@server.com" />
  <input type="hidden" name="lc" value="US" />
  <input type="hidden" name="item_name" value="Item Name" />
  <input type="hidden" name="amount" value="15" />
  <input type="hidden" name="currency_code" value="USD" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="no_shipping" value="1" />
  <input type="hidden" name="rm" value="1" />
  <input type="hidden" name="notify_url" value="http://www.yoursite.com/paypal_ipn/process" />
  <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_LG.gif:NonHosted" />
  <input type="submit" value="Pay" />
  <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>