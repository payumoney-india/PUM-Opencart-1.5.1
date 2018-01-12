<?php /* Opencart Module v1.0 for Citrus Payment Gateway - Copyrighted file - Please do not modify/refactor/disasseble/extract any or all part content  */ ?>
<?php 

	class ControllerPaymentPumcp extends Controller 
	{
	
		function generateHmacKey($data, $apiKey=null){
			$hmackey = hash_hmac('sha1',$data,$apiKey);
			return $hmackey;
		}	
		
		protected function index() 
		{
			$this->load->model('checkout/order');	
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			if($order_info['payment_method'] == 'PayUMoney') {
				$this->process_payu();
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pumcp_payu.tpl')) {
					$this->template= $this->config->get('config_template') . '/template/payment/pumcp_payu.tpl';	
				} else {
					$this->template= 'default/template/payment/pumcp_payu.tpl';
				}									
			}
		
			if($order_info['payment_method'] == 'CitrusPay') {
				$this->process_citrus();
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pumcp_citrus.tpl')) 
				{
					$this->template= $this->config->get('config_template') . '/template/payment/pumcp_citrus.tpl';	
				} 
				else 
				{
					$this->template= 'default/template/payment/pumcp_citrus.tpl';
				}	
			}
			$this->render();
		}
		
		private function process_citrus()
		{
			$this->data['button_confirm'] = $this->language->get('button_confirm');
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']); 
						
			$this->load->language('payment/pumcp');
			$this->data['citrus_module'] = $this->config->get('pumcp_module');
			$this->data['citrus_vanityurl'] = $this->config->get('pumcp_citrus_vanityurl');
			$this->data['citrus_access_key'] = $this->config->get('pumcp_citrus_access_key');
			$this->data['citrus_secret_key'] = $this->config->get('pumcp_citrus_secret_key');
			$this->data['citrus_merchant_trans_id'] = rand(10,90).'ORD'.$order_info['order_id'];
			$this->data['currency'] = $this->config->get('pumcp_currency');
			$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
			$ntotal = $this->currency->convert( $total, $this->session->data['currency'],$this->data['currency']);
			$this->data['total'] = sprintf("%.2f", $ntotal);
		
			$this->data['firstname'] = $order_info['payment_firstname'];
			$this->data['lastname'] = $order_info['payment_lastname'];
			$this->data['addr1'] = $order_info['payment_address_1'];
			$this->data['city'] = $order_info['payment_city'];
			$this->data['state'] = $order_info['payment_zone'];
			$this->data['zip'] = $order_info['payment_postcode'];
			$this->data['country'] = $order_info['payment_country'];
			$this->data['email'] = $order_info['email'];
			$this->data['phone'] = $order_info['telephone'];
				
			$vanityUrl = $this->data['citrus_vanityurl'];
			$currency =$this->data['currency'];	
			$merchantTxnId = $this->data['citrus_merchant_trans_id'];
			$orderAmount = $this->data['total'];		
			$tmpdata = "$vanityUrl$orderAmount$merchantTxnId$currency";
		
			$secSignature = $this->generateHmacKey($tmpdata,$this->data['citrus_secret_key']);
			$action = ""; 
		
			$this->data['action']=$action;
			$this->data['secSignature']=$secSignature;			
			$this->data['products'] = array();
			$products = $this->cart->getProducts();     
			foreach ($products as $product) 
			{
				$this->data['products'][] = array(
					'product_id'  => $product['product_id'],
					'name' => $product['name'],
					'description' => $product['name'],
					'quantity'    => $product['quantity'],
					'price'  => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], false));
			}	
			$this->data['lang'] = $this->session->data['language'];
			$this->data['vanity_url'] = $vanityUrl;
			$this->data['redir_url'] = $this->url->link('payment/pumcp/callback_citrus', '', 'SSL');			
			$this->data['notify_url'] = $this->url->link('payment/pumcp/callback_citrus', '', 'SSL');
		
			return;
		}
		
		public function callback_citrus() 
		{
			$this->load->model('checkout/order');
			$this->data['citrus_module'] = $this->config->get('pumcp_module');
			$this->data['citrus_secret_key'] = $this->config->get('pumcp_citrus_secret_key');
				
		
			$pdata = array();
			if (isset($_POST['TxStatus'])) {
				$pdata = $_POST;				
			}
			else
			{
				//invalid return data
				if($this->session) $this->session->data['error'] = "Transaction error ....";		
				if($this->response) $this->response->redirect($this->url->link('checkout/fail', '', 'SSL'));
				return;
			}
			
			//resp signature validation
			$str=$pdata['TxId'].$pdata['TxStatus'].$pdata['amount'].$pdata['pgTxnNo'].$pdata['issuerRefNo'].$pdata['authIdCode'].$pdata['firstName'].$pdata['lastName'].$pdata['pgRespCode'].$pdata['addressZip'];
		
			$respSig=$pdata['signature'];
		
			$order_id = substr(trim($pdata['TxId']),5);
			if($this->generateHmacKey($str,$this->data['citrus_secret_key']) == $respSig)
			{ 	
			
				if (strtoupper($pdata['TxStatus']) == 'SUCCESS')
				{
					$this->model_checkout_order->confirm($order_id,	$this->config->get('citrus_order_status_id'));  
					$this->redirect($this->url->link('checkout/success'));  
				}
				else
					$this->redirect($this->url->link('checkout/fail'));	 
			}
			else
			{
				$this->redirect($this->url->link('checkout/fail')); //forged 
			}
		}
		
		private function process_payu() {	
    		$this->data['button_confirm'] = $this->language->get('button_confirm');
			$this->load->model('checkout/order');
			$this->language->load('payment/pumcp');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			$this->data['merchant'] = $this->config->get('pumcp_payu_merchant');
		
		 /////////////////////////////////////Start Payu Vital  Information /////////////////////////////////
		
			if($this->config->get('pumcp_module')=='Sandbox')
				$this->data['action'] = 'https://sandboxsecure.payu.in/_payment.php';
			else
			    $this->data['action'] = 'https://secure.payu.in/_payment.php';
			
			$txnid        = 	$this->session->data['order_id'];

		             
			$this->data['key'] = $this->config->get('pumcp_payu_merchant');
			$this->data['salt'] = $this->config->get('pumcp_payu_salt');
			$this->data['txnid'] = $txnid;
			$this->data['amount'] = (int)$order_info['total'];
			$this->data['productinfo'] = 'opencart products information';
			$this->data['firstname'] = $order_info['payment_firstname'];
			$this->data['Lastname'] = $order_info['payment_lastname'];
			$this->data['Zipcode'] = $order_info['payment_postcode'];
			$this->data['email'] = $order_info['email'];
			$this->data['phone'] = $order_info['telephone'];
			$this->data['address1'] = $order_info['payment_address_1'];
    	    $this->data['address2'] = $order_info['payment_address_2'];
        	$this->data['state'] = $order_info['payment_zone'];
	        $this->data['city']=$order_info['payment_city'];
    	    $this->data['country']=$order_info['payment_country'];
			$this->data['Pg'] = 'CC';
			$this->data['surl'] = $this->url->link('payment/pumcp/callback_payu');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
			$this->data['Furl'] = $this->url->link('payment/pumcp/callback_payu');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
	  //$this->data['surl'] = $this->url->link('checkout/success');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
      //$this->data['furl'] = $this->url->link('checkout/cart');//HTTP_SERVER.'/index.php?route=payment/payu/callback';
			$this->data['curl'] = $this->url->link('payment/pumcp/callback_payu');
			$key          =  $this->config->get('pumcp_payu_merchant');
			$amount       = (int)$order_info['total'];
			$productInfo  = $this->data['productinfo'];
	    	$firstname    = $order_info['payment_firstname'];
			$email        = $order_info['email'];
			$salt         = $this->config->get('pumcp_payu_salt');
			$udf5 		  = "Opencart_v_1.6";
			$Hash=hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'||||||'.$salt); 
			$this->data['user_credentials'] = $this->data['key'].':'.$this->data['email'];
			$this->data['udf5'] = $udf5;
			$this->data['Hash'] = $Hash;
			$service_provider = 'payu_paisa';
			$this->data['service_provider'] = $service_provider;
					/////////////////////////////////////End Payu Vital  Information /////////////////////////////////		
	}
	
	public function callback_payu() {
		if (isset($this->request->post['key']) && ($this->request->post['key'] == $this->config->get('pumcp_payu_merchant'))) {
			$this->language->load('payment/pumcp');
			
			$this->load->model('checkout/order');
     		$orderid = $this->request->post['txnid'];
			$order_info = $this->model_checkout_order->getOrder($orderid);
			
			$this->data['title'] = sprintf($this->language->get('heading_title'), $order_info['payment_method']);

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $order_info['payment_method']);
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$this->data['text_failure'] = $this->language->get('text_failure');
			$this->data['text_cancelled'] = $this->language->get('text_cancelled');
			$this->data['text_cancelled_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/cart'));
			$this->data['text_pending'] = $this->language->get('text_pending');
			$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
			 
				$key          		=  	$this->request->post['key'];
				$amount      		= 	$this->request->post['amount'];
				$productInfo  		= 	$this->request->post['productinfo'];
				$firstname    		= 	$this->request->post['firstname'];
				$email        		=	$this->request->post['email'];
				$salt        		= 	$this->config->get('pumcp_payu_salt');
				$txnid		 		=   $this->request->post['txnid'];
				$udf5		 		=   $this->request->post['udf5'];
				$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
				$keyArray 	  		= 	explode("|",$keyString);
				$reverseKeyArray 	= 	array_reverse($keyArray);
				$reverseKeyString	=	implode("|",$reverseKeyArray);
			 
			 
			 if (isset($this->request->post['status']) && $this->request->post['status'] == 'success') {
			 	$saltString     = $salt.'|'.$this->request->post['status'].'|'.$reverseKeyString;
				$sentHashString = strtolower(hash('sha512', $saltString));
			 	$responseHashString=$this->request->post['hash'];
				
				$order_id = $this->request->post['txnid'];
				$message = '';
				$message .= 'orderId: ' . $this->request->post['txnid'] . "\n";
				$message .= 'Transaction Id: ' . $this->request->post['mihpayid'] . "\n";
				foreach($this->request->post as $k => $val){
					$message .= $k.': ' . $val . "\n";
				}
					if($sentHashString==$this->request->post['hash']){
							$this->model_checkout_order->confirm($this->request->post['txnid'],	$this->config->get('citrus_order_status_id'));  
							$this->data['continue'] = $this->url->link('checkout/success');
							
							$this->redirect($this->url->link('checkout/success'));  
					}			 
			 
			 }else {
    			$this->data['continue'] = $this->url->link('checkout/cart');
				
		        if(isset($this->request->post['status']) && $this->request->post['unmappedstatus'] == 'userCancelled')
				{
				 	$this->redirect($this->url->link('checkout/fail'));  
				}
				else {
					$this->redirect($this->url->link('checkout/fail'));  
				
				}					
			}
		}
	}
		
	}
?>