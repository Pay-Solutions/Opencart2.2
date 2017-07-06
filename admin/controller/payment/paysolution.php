<?php
class ControllerPaymentPaysolution extends Controller {
	private $error = array ();
	public function index() {
		$data = array ();
		$data = array_merge ( $data, $this->load->language ( 'payment/paysolution' ) );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'setting/setting' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && ($this->validate ())) {
			
			$this->load->model ( 'setting/setting' );
			
			$this->model_setting_setting->editSetting ( 'paysolution', $this->request->post );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->response->redirect ( $this->url->link ( 'extension/payment', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['email'] )) {
			$data ['error_email'] = $this->error ['email'];
		} else {
			$data ['error_email'] = '';
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_payment' ),
				'href' => $this->url->link ( 'extension/payment', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => ' :: ' 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'payment/paysolution', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => ' :: ' 
		);
		
		$data ['action'] = $this->url->link ( 'payment/paysolution', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['cancel'] = $this->url->link ( 'extension/payment', 'token=' . $this->session->data ['token'], 'SSL' );
		
		if (isset ( $this->request->post ['paysolution_merchantid'] )) {
			$data ['paysolution_merchantid'] = $this->request->post ['paysolution_merchantid'];
		} else {
			$data ['paysolution_merchantid'] = $this->config->get ( 'paysolution_merchantid' );
		}
		
		if (isset ( $this->request->post ['paysolution_transaction'] )) {
			$data ['paysolution_transaction'] = $this->request->post ['paysolution_transaction'];
		} else {
			$data ['paysolution_transaction'] = $this->config->get ( 'paysolution_transaction' );
		}
		
		if (isset ( $this->request->post ['paysolution_order_status_id'] )) {
			$data ['paysolution_order_status_id'] = $this->request->post ['paysolution_order_status_id'];
		} else {
			$data ['paysolution_order_status_id'] = $this->config->get ( 'paysolution_order_status_id' );
		}
		
		if (isset ( $this->request->post ['paysolution_order_status_id_cs'] )) {
			$data ['paysolution_order_status_id_cs'] = $this->request->post ['paysolution_order_status_id_cs'];
		} else {
			$data ['paysolution_order_status_id_cs'] = $this->config->get ( 'paysolution_order_status_id_cs' );
		}
		
		if (isset ( $this->request->post ['paysolution_order_status_id_failed'] )) {
			$data ['paysolution_order_status_id_failed'] = $this->request->post ['paysolution_order_status_id_failed'];
		} else {
			$data ['paysolution_order_status_id_failed'] = $this->config->get ( 'paysolution_order_status_id_failed' );
		}
		
		$this->load->model ( 'localisation/order_status' );
		
		$data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
		
		if (isset ( $this->request->post ['paysolution_status'] )) {
			$data ['paysolution_status'] = $this->request->post ['paysolution_status'];
		} else {
			$data ['paysolution_status'] = $this->config->get ( 'paysolution_status' );
		}
		
		if (isset ( $this->request->post ['paysolution_sort_order'] )) {
			$data ['paysolution_sort_order'] = $this->request->post ['paysolution_sort_order'];
		} else {
			$data ['paysolution_sort_order'] = $this->config->get ( 'paysolution_sort_order' );
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'payment/paysolution.tpl', $data ) );
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'payment/paysolution' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->request->post ['paysolution_merchantid']) {
			$this->error ['email'] = $this->language->get ( 'error_merchantid' );
		}
		
		return ! $this->error;
	}
}
?>
