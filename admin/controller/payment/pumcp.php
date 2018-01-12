<?php /* Opencart Module v1.0 for Citrus Payment Gateway - Copyrighted file - Please do not modify/refactor/disasseble/extract any or all part content  */ ?>
<?php 
	class ControllerPaymentPumcp extends Controller 
	{
		private $error = array();
		
		public function index() 
		{
			$this->load->language('payment/pumcp');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->load->model('setting/setting');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
			{
				$this->model_setting_setting->editSetting('pumcp', $this->request->post);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['entry_route'] = $this->language->get('entry_route');
			$this->data['entry_route_payu'] = $this->language->get('entry_route_payu');
			$this->data['entry_route_citrus'] = $this->language->get('entry_route_citrus');
			$this->data['help_route'] = $this->language->get('help_route');
			$this->data['entry_module'] = $this->language->get('entry_module');
			$this->data['entry_module_id'] = $this->language->get('entry_module_id');
			$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
			$this->data['entry_order_fail_status'] = $this->language->get('entry_order_fail_status');	
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_currency'] = $this->language->get('entry_currency');
			$this->data['help_currency'] = $this->language->get('help_currency');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_all_zones'] = $this->language->get('text_all_zones');		
					
			$this->data['entry_merchant'] = $this->language->get('entry_merchant');
			$this->data['entry_salt'] = $this->language->get('entry_salt');
			$this->data['entry_test'] = $this->language->get('entry_test');
			$this->data['entry_total'] = $this->language->get('entry_total');	
			
			$this->data['entry_vanityurl'] = $this->language->get('entry_vanityurl');
			$this->data['entry_access_key'] = $this->language->get('entry_access_key');
			$this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
		
			$this->data['help_merchant'] = $this->language->get('help_merchant');
			$this->data['help_vanityurl'] = $this->language->get('help_vanityurl');
			$this->data['help_accesskey'] = $this->language->get('help_accesskey');
			$this->data['help_secretkey'] = $this->language->get('help_secretkey');
			$this->data['help_total'] = $this->language->get('help_total');
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
    	    $this->data['help_salt'] = $this->language->get('help_salt');
			
			
			$this->data['breadcrumbs'] = array();   
			$this->data['breadcrumbs'][] = array('text'=> $this->language->get('heading_title'),'href'=> $this->url->link('payment/pumcp', 'token=' . $this->session->data['token'], 'SSL'),'separator' => ' :: ');
			$this->data['action'] = $this->url->link('payment/pumcp', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
			
			if ($this->error) {
				$this->data = array_merge($this->data,$this->error);
			} 
			
			if (isset($this->request->post['pumcp_citrus_vanityurl'])) 
			{
				$this->data['pumcp_citrus_vanityurl'] = $this->request->post['pumcp_citrus_vanityurl'];
			} 
			else 
			{
				$this->data['pumcp_citrus_vanityurl'] = $this->config->get('pumcp_citrus_vanityurl');
			} 
			if (isset($this->request->post['pumcp_citrus_access_key'])) 
			{
				$this->data['pumcp_citrus_access_key'] = $this->request->post['pumcp_citrus_access_key'];
			} 
			else 
			{
				$this->data['pumcp_citrus_access_key'] = $this->config->get('pumcp_citrus_access_key');
			}
			if (isset($this->request->post['pumcp_citrus_secret_key'])) 
			{
				$this->data['pumcp_citrus_secret_key'] = $this->request->post['pumcp_citrus_secret_key'];
			} 
			else 
			{
				$this->data['pumcp_citrus_secret_key'] = $this->config->get('pumcp_citrus_secret_key');
			}

			if (isset($this->request->post['pumcp_route_payu'])) {
				$this->data['pumcp_route_payu'] = $this->request->post['pumcp_route_payu'];
			} else {
				$this->data['pumcp_route_payu'] = $this->config->get('pumcp_route_payu');
			}
		
			if (isset($this->request->post['pumcp_route_citrus'])) {
				$this->data['pumcp_route_citrus'] = $this->request->post['pumcp_route_citrus'];
			} else {
				$this->data['pumcp_route_citrus'] = $this->config->get('pumcp_route_citrus');
			}
		
			if($this->data['pumcp_route_payu'] == '' && $this->data['pumcp_route_citrus'] == '') 
			{
				$this->data['pumcp_route_payu'] = '50';
				$this->data['pumcp_route_citrus'] = '50';
			}
		
			if (isset($this->request->post['pumcp_payu_merchant'])) {
				$this->data['pumcp_payu_merchant'] = $this->request->post['pumcp_payu_merchant'];
			} else {
				$this->data['pumcp_payu_merchant'] = $this->config->get('pumcp_payu_merchant');
			}
		
			if (isset($this->request->post['pumcp_payu_salt'])) {
				$this->data['pumcp_payu_salt'] = $this->request->post['pumcp_payu_salt'];
			} else {
				$this->data['pumcp_payu_salt'] = $this->config->get('pumcp_payu_salt');
			}
		
			if (isset($this->request->post['pumcp_total'])) {
				$this->data['pumcp_total'] = $this->request->post['pumcp_total'];
			} else {
				$this->data['pumcp_total'] = $this->config->get('pumcp_total'); 
			} 
		
			if (isset($this->request->post['pumcp_currency'])) {
				$this->data['pumcp_currency'] = $this->request->post['pumcp_currency'];
			} else {
				$this->data['pumcp_currency'] = $this->config->get('pumcp_currency'); 
			} 
				
			if (isset($this->request->post['pumcp_order_status_id'])) {
				$this->data['pumcp_order_status_id'] = $this->request->post['pumcp_order_status_id'];
			} else {
				$this->data['pumcp_order_status_id'] = $this->config->get('pumcp_order_status_id'); 
			} 

			if (isset($this->request->post['pumcp_order_fail_status_id'])) {
				$this->data['pumcp_order_fail_status_id'] = $this->request->post['pumcp_order_fail_status_id'];
			} else {
				$this->data['pumcp_order_fail_status_id'] = $this->config->get('pumcp_order_fail_status_id'); 
			} 
		
			$this->load->model('localisation/order_status');
		
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
			if (isset($this->request->post['pumcp_geo_zone_id'])) {
				$this->data['pumcp_geo_zone_id'] = $this->request->post['pumcp_geo_zone_id'];
			} else {
				$this->data['pumcp_geo_zone_id'] = $this->config->get('pumcp_geo_zone_id'); 
			} 
		
			$this->load->model('localisation/geo_zone');
										
			$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
			
			if (isset($this->request->post['pumcp_status'])) {
				$this->data['pumcp_status'] = $this->request->post['pumcp_status'];
			} else {
				$this->data['pumcp_status'] = $this->config->get('pumcp_status');
			}
		
			if (isset($this->request->post['pumcp_sort_order'])) {
				$this->data['pumcp_sort_order'] = $this->request->post['pumcp_sort_order'];
			} else {
				$this->data['pumcp_sort_order'] = $this->config->get('pumcp_sort_order');
			}
			$this->template = 'payment/pumcp.tpl';
			$this->children = array('common/header','common/footer'); 
			$this->response->setOutput($this->render());
		}
		
		private function validate() 
		{
			$flag=false;
		
			if (!$this->user->hasPermission('modify', 'payment/pumcp')) {
				$this->error['error_warning'] = $this->language->get('error_permission');
			}
		
			//PayU both parameters mandatory
			if($this->request->post['pumcp_payu_merchant'] || $this->request->post['pumcp_payu_salt']) {
				if (!$this->request->post['pumcp_payu_merchant']) {
					$this->error['error_merchant'] = $this->language->get('error_merchant');
				}
			
				if (!$this->request->post['pumcp_payu_salt']) {
					$this->error['error_salt'] = $this->language->get('error_salt');
				}
			}
			if($this->request->post['pumcp_payu_merchant'] && $this->request->post['pumcp_payu_salt']) {
				$flag=true;	
			}
			//Citrus all three parameters mandatory
			if($this->request->post['pumcp_citrus_vanityurl'] || $this->request->post['pumcp_citrus_access_key'] || $this->request->post['pumcp_citrus_secret_key']) {
				if (!$this->request->post['pumcp_citrus_vanityurl']) 
				{
					$this->error['error_citrus_vanityurl'] = $this->language->get('error_vanityrul');
				}
				if (!$this->request->post['pumcp_citrus_access_key']) 
				{
					$this->error['error_citrus_access_key'] = $this->language->get('error_accesskey');
				}
				if (!$this->request->post['pumcp_citrus_secret_key']) 
				{
					$this->error['error_citrus_secret_key'] = $this->language->get('error_secretkey');
				}
			}
			if($this->request->post['pumcp_citrus_vanityurl'] && $this->request->post['pumcp_citrus_access_key'] && $this->request->post['pumcp_citrus_secret_key']) {
				$flag=true;	
			}
			$rpu=(int) (($this->request->post['pumcp_route_payu'])? $this->request->post['pumcp_route_payu']: 0);
			$rcp=(int) (($this->request->post['pumcp_route_citrus'])? $this->request->post['pumcp_route_citrus']: 0);
			if(($rpu + $rcp) != 100) {
				$this->error['error_route'] = $this->language->get('error_route');
			}
		
			if (!$this->request->post['pumcp_module']) {
				$this->error['error_module'] = $this->language->get('error_module');
			}
			
			if(!$this->request->post['pumcp_currency'] || strlen($this->request->post['pumcp_currency']) < 3)
			{
				$this->error['error_currency'] = $this->language->get('error_currency');
			}
			else {
				$this->request->post['pumcp_currency'] = strtoupper($this->request->post['pumcp_currency']);
			}
			
			if(!$flag && $this->request->post['pumcp_status'] == '1')
			{
				$this->error['error_status'] = $this->language->get('error_status');
			}
		
			if (!$this->error) {
				return true;
			} else {
				return false;
			}	
		}
	}
?>