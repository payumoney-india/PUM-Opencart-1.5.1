<?php 
class ModelPaymentPayu extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/payu');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payu_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('payu_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payu_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true; 
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'payu',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('payu_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
public function cleanString($string) {

         $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($string))))));
        return $clear;
    }

	public function getOptions($orderId){

		$Jsonstring = 'NULL';
		$query = $this->db->query("SELECT `product_id` FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$orderId."'");
		$productInfo = array();
                $productInfo2 = array();
		if ($query->num_rows) {		
			foreach ($query->rows as $ordered_product){
			
				$productQuery = $this->db->query("SELECT P.price AS price,PD.name AS name,PD.description AS description FROM " . DB_PREFIX . "product_description as PD,".DB_PREFIX. "product as P WHERE P.product_id =PD.product_id AND P.product_id='" . $ordered_product['product_id']."'");
			
				$productInfo['name'] = $this->cleanString($productQuery->row['name']);
				$productInfo['description'] =  $this->cleanString(substr($productQuery->row['description'],0,50));
				//$productInfo['description'] = 'desc';
				$productInfo['value'] = $productQuery->row['price'];
				$productInfo['isRequired'] = "true";
				$productInfo['settlementEvent'] = "EmailConfirmation";
				$productInfo2[] = $productInfo;
			}
		}
		
		$productIndoFilterData['paymentParts'] = $productInfo2;

		$jsonProductInfo = json_encode($productIndoFilterData);

	return $jsonProductInfo;

	}
	
	
	
}
?>
