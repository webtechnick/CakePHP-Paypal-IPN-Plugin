<?php
class InstantPaymentNotificationsController extends PaypalIpnAppController {

	public $name = 'InstantPaymentNotifications';
	public $helpers = array('Html', 'Form');

/**
 * beforeFilter makes sure the process is allowed by auth
 *  since paypal will need direct access to it.
 */
	public function beforeFilter(){
		parent::beforeFilter();
		if (isset($this->Auth)) {
			$this->Auth->allow('process');
		}
		if (isset($this->Security) && $this->action == 'process') {
		  $this->Security->validatePost = false;
		}
	}

/**
 * Paypal IPN processing action..
 * Intake for a paypal_ipn callback performed by paypal itself.
 * This action will take the paypal callback, verify it (so trickery) and
 * save the transaction into your database for later review
 *
 * @access public
 * @author Nick Baker
 */
	public function process() {
		$this->autoRender = false;
		$this->log('Process accessed', 'paypal');
		if ($this->request->is('post')) {
			$this->log('POST ' . print_r($_POST, true), 'paypal');
		}
		if ($this->InstantPaymentNotification->isValid($_POST)) {
			$this->log('POST Valid', 'paypal');
			$notification = $this->InstantPaymentNotification->buildAssociationsFromIPN($_POST);

			$existingIPNId = $this->InstantPaymentNotification->searchIPNId($notification);
			if ($existingIPNId !== false) {
				$notification['InstantPaymentNotification']['id'] = $existingIPNId;
			}

			$this->InstantPaymentNotification->saveAll($notification);
			$this->__processTransaction($this->InstantPaymentNotification->id);
		} else {
			$this->log('POST Not Validated', 'paypal');
		}
		return $this->redirect('/');
	}

/**
 * __processTransaction is a private callback function used to log a verified transaction
 * @access private
 * @param String $txnId is the string paypal ID and the id used in your database.
 */
	private function __processTransaction($txnId){
		$this->log("Processing Trasaction: {$txnId}", 'paypal');
		//Put the afterPaypalNotification($txnId) into your app_controller.php
		$this->afterPaypalNotification($txnId);
	}

/**
 * Admin Only Functions... all baked
 */

/**
 * Admin Index
 */
	public function admin_index() {
		$this->InstantPaymentNotification->recursive = 0;
		$this->set('instantPaymentNotifications', $this->paginate());
	}

/**
 * Admin View
 * @param String ID of the transaction to view
 */
	public function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid InstantPaymentNotification.'));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('instantPaymentNotification', $this->InstantPaymentNotification->read(null, $id));
	}

/**
 * Admin Add
 */
	public function admin_add(){
		 $this->redirect(array('admin' => true, 'action' => 'edit'));
	}

/**
 * Admin Edit
 * @param String ID of the transaction to edit
 */
	public function admin_edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->InstantPaymentNotification->save($this->request->data)) {
				$this->Session->setFlash(__('The InstantPaymentNotification has been saved'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The InstantPaymentNotification could not be saved. Please, try again.'));
			}
		}
		if ($id && empty($this->data)) {
			$this->request->data = $this->InstantPaymentNotification->read(null, $id);
		}
	}

/**
 * Admin Delete
 * @param String ID of the transaction to delete
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for InstantPaymentNotification'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->InstantPaymentNotification->delete($id)) {
			$this->Session->setFlash(__('InstantPaymentNotification deleted'));
			$this->redirect(array('action'=>'index'));
		}
	}

}