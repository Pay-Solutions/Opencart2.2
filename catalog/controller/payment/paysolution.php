<?php
class ControllerPaymentPaysolution extends Controller {
	public function manage() {
		// $this->cart->clear ();
		header ( "Content-Type: text/plain; charset=utf-8" );
		$values = "";
		
		$datas = array_merge ( $this->request->post, $this->request->get );
		foreach ( $datas as $key => $value ) {
			$values .= "$key=$value&";
		}
		$values = substr ( $values, 0, strlen ( $values ) - 1 );
		$action = 'https://www.thaiepay.com/epaylink/payment.aspx?' . $values;
		$this->response->redirect ( $action );
	}
	public function index() {
		$data = array_merge ( array (), $this->language->load ( 'payment/paysolution' ) );
		
		$data ['paysolution'] = array ();
		
		$this->load->model ( 'checkout/order' );
		$order_info = $this->model_checkout_order->getOrder ( $this->session->data ['order_id'] );
		
		$data ['paysolution'] = array_merge ( $data ['paysolution'], $order_info );
		
		$paysolution_languages = array (
				'th' => 't',
				'en' => 'e',
				'jp' => 'j',
				'cn' => 'c',
				'nl' => 'e' 
		);
		
		if (array_key_exists ( $this->session->data ['language'], $paysolution_languages )) {
			$lang = $paysolution_languages [$this->session->data ['language']];
		} else {
			$lang = 't'; // default Thai language
		}
		
		$data ['action'] = 'https://www.thaiepay.com/epaylink/payment.aspx?lang=' . $lang;
		
		// if ($order_info) {
		$data ['paysolution'] ['merchantid'] = html_entity_decode ( $this->config->get ( 'paysolution_merchantid' ), ENT_QUOTES, 'UTF-8' );
		
		$data ['paysolution'] ['invoice'] = $this->session->data ['order_id'];
		$data ['paysolution'] ['price'] = $this->currency->format ( $order_info ['total'], $order_info ['currency_code'], $order_info ['currency_value'], false );
		$data ['products'] = array ();
		
		foreach ( $this->cart->getProducts () as $product ) {
			$data ['products'] [] = array (
					'name' => $product ['name'] 
			);
			$title_item [] = $product ['name'];
		}
		$data ['paysolution'] ['productdetail'] = implode ( ",", $title_item );
		
		// $cur = $this->currency->getCode (); // maxx : remove after opencart 2.2
		$cur = $this->config->get ( 'config_currency' ); // maxx : use this line for after opencart 2.2
		
		$currencies = array (
				'AUD',
				'CAD',
				'EUR',
				'GBP',
				'JPY',
				'USD',
				'NZD',
				'CHF',
				'HKD',
				'SGD',
				'SEK',
				'DKK',
				'PLN',
				'NOK',
				'HUF',
				'CZK',
				'ILS',
				'MXN',
				'MYR',
				'BRL',
				'PHP',
				'TWD',
				'THB',
				'TRY',
				'THB' 
		);
		
		$paysolution_currencies = array (
				'THB' => '764',
				'AUD' => '036',
				'GBP' => '826',
				'EUR' => '978',
				'HKD' => '344',
				'JPY' => '392',
				'NZD' => '554',
				'SGD' => '702',
				'CHF' => '756',
				'USD' => '840' 
		);
		
		if (in_array ( $order_info ['currency_code'], $currencies )) {
			if (array_key_exists ( $order_info ['currency_code'], $paysolution_currencies )) {
				$currency = $paysolution_currencies [$order_info ['currency_code']];
				// $currency = $order_info['currency_code'];
			} else {
				$currency = '764'; // Default THB
			}
		} else {
			$currency = '764'; // Default THB
		}
		
		$data ['paysolution'] ['currencyCode'] = $currency;
		$data ['paysolution'] ['postURL'] = $this->url->link ( 'payment/paysolution/callback' );
		$data ['paysolution'] ['reqURL'] = $this->url->link ( 'payment/paysolution/callbackground' );
		$data ['paysolution'] ['customeremail'] = $this->customer->getEmail ();
		$data ['paysolution'] ['refno'] = $data ['paysolution'] ['invoice_no'];
		
		// $data ['paymentaction'] = ($this->config->get ( 'paysolution_transaction' ) == "1") ? 'Payment' : 'Test';
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/payment/paysolution.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/payment/paysolution.tpl', $data );
		} else {
			return $this->load->view ( 'payment/paysolution.tpl', $data );
		}
		
		// $this->model_checkout_order->confirm($this->session->data['order_id'], "1");
		// } // maxx : end $order_info check
	}
	public function callback() {
		$this->load->model ( 'payment/paysolution' );
		$this->load->model ( 'checkout/order' );
		
		$refno = $this->request->post ['refno'];
		$total = $this->request->post ['total'];
		$merchantid = $this->request->post ['merchantid'];
		$status = $this->request->post ['status'];
		
		$error = '';
		
		if ($status == "CP") {
			$this->model_checkout_order->confirm ( $refno, $this->config->get ( 'paysolution_order_status_id' ) );
		}
	}
}
?>
