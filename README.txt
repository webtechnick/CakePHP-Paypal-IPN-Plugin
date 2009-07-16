Paypal IPN plugin.  (Paypal Instant Payment Notification)
Version 1.5
Author: Nick Baker (nick@webtechnick.com)
Website: http://www.webtechnick.com

Browse/Download: http://projects.webtechnick.com/paypal_ipn
SVN: https://svn2.xp-dev.com/svn/nurvzy-paypal-ipn

Special thanks: Peter Butler <http://www.studiocanaria.com>

Install:
1) Copy plugin into your /app/plugins/paypal_ipn directory
2) Run the paypal_ipn.sql into your database.
3) Add the following into your /app/config/routes.php file:
  /* Paypal IPN plugin */
  Router::connect('/paypal_ipn/process', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'process'));
  
  /* Optional Routes, but nice for administration */
  Router::connect('/paypal_ipn/edit/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'edit'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/view/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'view'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/delete/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'delete'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/add', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'edit'));
  Router::connect('/paypal_ipn', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'index'));/*
  /* End Paypal IPN plugin */
  
Paypal Setup:
1) I suggest you start a sandbox account at https://developer.paypal.com
2) Enable IPN in your account.
  
Administration: (optional) If you want to use the built in admin access to IPNs:
1) Make sure you're logged in as an Administrator via the Auth component.
2) Navigate to www.yoursite.com/paypal_ipn


Paypal Button Helper: (optional) if you plan on using the paypal helper for your PayNow or Subscribe Buttons
1) Update /paypal_ipn/config/paypal_ipn_config.php with your paypal information
2) Add 'PaypalIpn.Paypal' to your helpers list in app_controller.php:
       var $helpers = array('Html','Form','PaypalIpn.Paypal');
3) Usage: (view the actual /paypal_ipn/views/helpers/paypal.php for more information)
       $paypal->button(String tittle, Options array);
       Examples: 
         $paypal->button('Pay Now', array('amount' => '12.00', 'item_name' => 'test item'));
         $paypal->button('Subscribe', array('type' => 'subscribe', 'amount' => '60.00', 'term' => 'month', 'period' => '2'));
         $paypal->button('Donate', array('type' => 'donate', 'amount' => '60.00'));
         $paypal->button('Add To Cart', array('type' => 'addtocart', 'amount' => '15.00'));
       Test Example:
         $paypal->button('Pay Now', array('test' => true, 'amount' => '12.00', 'item_name' => 'test item'));

Paypal Button:
1) Use the PaypalHelper to generate your buttons for you. See Paypal Button Helper (above) for more.
 - or -
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


Paypal Notification Callback:
1) create a function in your /app/app_controller.php like so:

  function afterPaypalNotification($txnId){
    $this->log('Im in the afterPaypalNotification', 'paypal');
    //Here is where you can implement code to apply the transaction to your app.
    //for example, you could now mark an order as paid, a subscription, or give the user premium access.
    //retrieve the transaction using the txnId passed and apply whatever logic your site needs.
  }
