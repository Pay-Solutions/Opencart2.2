<?php
class ModelPaymentPaysolution extends Model {
	public function getMethod($address, $total) {
		$this->load->language ( 'payment/paysolution' );
		if ($this->config->get ( 'paysolution_status' )) {
			$status = TRUE;
		} else {
			$status = FALSE;
		}
		$method_data = array ();
		if ($status) {
			$method_data = array (
					'code' => 'paysolution',
					'title' => $this->language->get ( 'text_title' ),
					'logo' => $this->language->get ( 'paysolution_logo' ),
					'terms' => '',
					'sort_order' => $this->config->get ( 'paysolution_sort_order' ) 
			);
		}
		
		return $method_data;
	}
}
?>
