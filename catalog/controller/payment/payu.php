<?php
class ControllerPaymentPayu extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		$this->load->model('payment/payu');
		$this->language->load('payment/payu');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->data['merchant'] = $this->config->get('payu_merchant');
	
		 
		 /////////////////////////////////////Start Payu Vital  Information /////////////////////////////////
		
		if($this->config->get('payu_test')=='demo')
			$this->data['action'] = 'https://test.payu.in/_payment.php';
		else
		    $this->data['action'] = 'https://secure.payu.in/_payment.php';
			
		$txnid        = 	$this->session->data['order_id'];
		$this->data['key'] = $this->config->get('payu_merchant');
		$this->data['salt'] = $this->config->get('payu_salt');
		$this->data['txnid'] = $txnid;
		$this->data['amount'] = (float)$order_info['total'];
		$this->data['productinfo'] = 'opencart products information';
		//$this->data['productinfo'] = $this->model_payment_payu->getOptions($this->session->data['order_id']);
		//echo "<pre>";print_r($this->data['productinfo']);die();			
		$this->data['firstname'] = $order_info['payment_firstname'];
		$this->data['Lastname'] = $order_info['payment_lastname'];
		$this->data['address'] = $order_info['payment_address_1'];
                $this->data['City'] = $order_info['payment_city'];
                $this->data['State'] = $order_info['payment_zone'];
                $this->data['Country'] = $order_info['payment_country'];
                 $this->data['Zipcode'] = $order_info['payment_postcode'];
                $this->data['ship_name'] = $order_info['shipping_firstname']." ".$order_info['shipping_lastname'];
                $this->data['ship_address'] = $order_info['shipping_address_1'];
                $this->data['ship_zipcode'] = $order_info['payment_postcode'];
                $this->data['ship_city'] = $order_info['shipping_city'];
                 $this->data['ship_state'] = $order_info['shipping_zone'];
                  $this->data['ship_country'] = $order_info['shipping_country'];
                   $this->data['ship_phone'] = $order_info['telephone'];

       
                $this->data['email'] = $order_info['email'];
		$this->data['phone'] = $order_info['telephone'];
		$this->data['pg'] = 'CC';
		$this->data['surl'] = $this->url->link('payment/payu/callback');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
		$this->data['furl'] = $this->url->link('payment/payu/callback');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
		$this->data['curl'] = $this->url->link('checkout/checkout', '', 'SSL');
                $this->data['service_provider'] = 'payu_paisa';
                $this->data['udf2'] = $txnid;
                 $this->data['website'] =HTTP_SERVER;
		$key          =  $this->config->get('payu_merchant');
		$amount       = $this->data['amount'];
		$productInfo  = $this->data['productinfo'];
		$firstname    = $order_info['payment_firstname'];
		$email        = $order_info['email'];
		$salt         = $this->config->get('payu_salt');
        $udf2         = $this->data['udf2'];
        $hash_str=$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'||'.$udf2.'|||||||||'.$salt;
		 $Hash=hash('sha512',$hash_str ); 
		
		$this->data['hash'] = $Hash;
			/////////////////////////////////////End Payu Vital  Information /////////////////////////////////
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/payu.tpl';
		} else {
			$this->template = 'default/template/payment/payu.tpl';
		}	
		
		$this->render();	
		
	}
	
	public function callback() {
		if (isset($this->request->post['key']) && ($this->request->post['key'] == $this->config->get('payu_merchant'))) {
			$this->language->load('payment/payu');
			
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$this->data['text_failure'] = $this->language->get('text_failure');
			$this->data['text_cancelled'] = $this->language->get('text_cancelled');
			$this->data['text_cancelled_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/cart'));
			$this->data['text_pending'] = $this->language->get('text_pending');
			$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
			
			 $this->load->model('checkout/order');
			 $orderid = $this->request->post['udf2'];
             $order_info = $this->model_checkout_order->getOrder($orderid);
			 
				$key          		=  	$this->request->post['key'];
				$amount      		= 	$this->request->post['amount'];
				$productInfo  		= 	$this->request->post['productinfo'];
				$firstname    		= 	$this->request->post['firstname'];
				$email        		=	$this->request->post['email'];
				$salt        		= 	$this->config->get('payu_salt');
				$txnid		 		=   $this->request->post['txnid'];
                $udf2        		= 	$this->request->post['udf2'];
				$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'||'.$udf2.'||||||||';
				$keyArray 	  		= 	explode("|",$keyString);
				$reverseKeyArray 	= 	array_reverse($keyArray);
				$reverseKeyString	=	implode("|",$reverseKeyArray);
			 
			 if (isset($this->request->post['status']) && $this->request->post['status'] == 'success') {
			 	$saltString     = $salt.'|'.$this->request->post['status'].'|'.$reverseKeyString;
				$sentHashString = strtolower(hash('sha512', $saltString));
			 	$responseHashString=$this->request->post['hash'];
				
				$order_id = $this->request->post['udf2'];
				$message = '';
				$message .= 'orderId: ' . $order_id . "\n";
				$message .= 'Transaction Id: ' . $this->request->post['mihpayid'] . "\n";
				foreach($this->request->post as $k => $val){
					$message .= $k.': ' . $val . "\n";
				}
					if($sentHashString==$this->request->post['hash']){
      
							$this->model_checkout_order->confirm($this->request->post['udf2'], $this->config->get('payu_order_status_id'));
							$this->model_checkout_order->update($this->request->post['udf2'], $this->config->get('payu_order_status_id'), $message,false);
							$this->data['continue'] = $this->url->link('checkout/success');
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_success.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/payu_success.tpl';
							} else {
								$this->template = 'default/template/payment/payu_success.tpl';
							}	
							
							$this->children = array(
								'common/column_left',
								'common/column_right',
								'common/content_top',
								'common/content_bottom',
								'common/footer',
								'common/header'
							);
								
							$this->response->setOutput($this->render());
					  } else {die;
						//Transaction will be pending
							$this->model_checkout_order->confirm($this->request->post['udf2'],1);
							$this->model_checkout_order->update($this->session->data['order_id'], 1, $message, false);
							$this->data['continue'] = $this->url->link('checkout/checkout', '', 'SSL');
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_pending.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/payu_pending.tpl';
							} else {
								$this->template = 'default/template/payment/payu_pending.tpl';
							}	
							$this->children = array(
								'common/column_left',
								'common/column_right',
								'common/content_top',
								'common/content_bottom',
								'common/footer',
								'common/header'
							);
							$this->response->setOutput($this->render());		
					  } 	
			 
			 
			 }else {
    			$this->data['continue'] = $this->url->link('checkout/cart');
                          $this->model_checkout_order->confirm($this->request->post['udf2'],1);
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/payu_failure.tpl';
				} else {
					$this->template = 'default/template/payment/payu_failure.tpl';
				}	
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
				$this->response->setOutput($this->render());						
			}
		}
	}
}
?>
