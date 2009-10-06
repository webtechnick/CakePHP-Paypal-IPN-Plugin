Paypal IPN plugin.  (Paypal Instant Payment Notification)
Version 2.2.2
Author: Nick Baker (nick@webtechnick.com)
Website: http://www.webtechnick.com

Browse/Download: http://projects.webtechnick.com/paypal_ipn
SVN: http://svn2.xp-dev.com/svn/nurvzy-paypal-ipn

CHANGELOG:
1.0: Initial release
1.1: Added cleaner routes
2.0: Helper added
2.1: Added cake schema install script
2.2: Added paypal unsubscribe type
2.2.1: Bug fix with subscription issues
2.2.2: Fixed validation issues with paypal button in strict doctype

Special thanks: Peter Butler <http://www.studiocanaria.com>

Install:
1) Copy plugin into your /app/plugins/paypal_ipn directory
2a) Run the paypal_ipn.sql into your database.
2b) run "cake schema run create -path plugins/paypal_ipn/config/sql -name ipn" in a terminal.
3) Add the following into your /app/config/routes.php file (optional):
  /* Paypal IPN plugin */
  Router::connect('/paypal_ipn/process', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'process'));
  /* Optional Route, but nice for administration */
  Router::connect('/paypal_ipn/:action/*', array('admin' => 'true', 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'index'));
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
  ...
  <input type="hidden" name="notify_url" value="http://www.yoursite.com/paypal_ipn/process" />
  ...
</form>


Paypal Notification Callback:
1) create a function in your /app/app_controller.php like so:

  function afterPaypalNotification($txnId){
    //Here is where you can implement code to apply the transaction to your app.
    //for example, you could now mark an order as paid, a subscription, or give the user premium access.
    //retrieve the transaction using the txnId passed and apply whatever logic your site needs.
      
    $transaction = ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->findById($txnId);
    $this->log($transaction['InstantPaymentNotification']['id'], 'paypal');
  
    //Tip: be sure to check the payment_status is complete because failure 
    //     are also saved to your database for review.
  
    if($transaction['InstantPaymentNotification']['payment_status'] == 'Completed'){
      //Yay!  We have monies!
    }
    else {
      //Oh no, better look at this transaction to determine what to do; like email a decline letter.
    }
  } 
