<?php
class InstantPaymentNotificationsController extends PaypalIpnAppController {

	var $name = 'InstantPaymentNotifications';
	var $helpers = array('Html', 'Form');
	
	function beforeFilter(){
	  parent::beforeFilter();
	  $this->Auth->allow('process');
	}
	
	/*****************
	  * Paypal IPN processing action.
	  */
	function process(){
    //Have we been sent an IPN here...
    if(!empty($_POST)){
      //...we have so add 'cmd' 'notify-validate' to a transaction variable
      $transaction = 'cmd=_notify-validate';
      //and add everything paypal has sent to the transaction
      foreach($_POST as $key => $value){
        $value = urlencode(stripslashes($value));
        $transaction .= "&$key=$value";
      }
      //create headers for post back
      $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
      $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $header .= "Content-Length: " . strlen($transaction) . "\r\n\r\n";
      
      //If this is a sandbox transaction then 'test_ipn' will be set to '1'
      if(isset($_POST['test_ipn'])) {
        $server = 'www.sandbox.paypal.com';
      } else {
        $server = 'www.paypal.com';
      }
        
      //and post the transaction back for validation
      $fp = fsockopen ("ssl://$server", 443, $errno, $errstr, 30);
      //Check we got a connection and response...
      if (!$fp) {
        //...didn't get a response so log error in error logs
        $this->log('HTTP Error in InstantPaymentNotifications::process while posting back to PayPal: Transaction='.$transaction);
      } else {
        //...got a response, so we'll through the response looking for VERIFIED or INVALID
        fputs($fp, $header . $transaction);
        while (!feof($fp)) {
          $response = fgets ($fp, 1024);
          if (strcmp($response, "VERIFIED") == 0) {
            //The response is VERIFIED so format the $_POST for processing
            $notification = array();
            $notification['InstantPaymentNotification']=$_POST;
            $this->InstantPaymentNotification->save($notification);
            $this->__processTransaction($this->InstantPaymentNotification->id);
          }
          elseif(strcmp ($response, "INVALID") == 0) {
            //The response is INVALID so log it for investigation
            $this->log("Found Invalid: $transaction",'paypal');
          }
        }
        fclose ($fp);
      }
    }
    //Redirect
    $this->redirect('/');
  }
  
  private function __processTransaction($txnId){
    $this->log("Processing Trasaction: $txnId",'paypal');
    //Here is where you can implement code to apply the transaction to your system
    //for example, you could now mark an order as paid, create a subscription, just
    //retrieve the transaction using the txn_id passed and apply whatever logic your site
    //needs.
  }
	
	/***********
	  * Admin Only
	  */
	function admin_index() {	  
		$this->InstantPaymentNotification->recursive = 0;
		$this->set('instantPaymentNotifications', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid InstantPaymentNotification.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('instantPaymentNotification', $this->InstantPaymentNotification->read(null, $id));
	}
	
	function admin_add(){
	   $this->redirect(array('admin' => true, 'action' => 'edit')); 
	}

	function admin_edit($id = null) {
		if (!empty($this->data)) {
			if ($this->InstantPaymentNotification->save($this->data)) {
				$this->Session->setFlash(__('The InstantPaymentNotification has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The InstantPaymentNotification could not be saved. Please, try again.', true));
			}
		}
		if ($id && empty($this->data)) {
			$this->data = $this->InstantPaymentNotification->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for InstantPaymentNotification', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->InstantPaymentNotification->del($id)) {
			$this->Session->setFlash(__('InstantPaymentNotification deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
}
?>