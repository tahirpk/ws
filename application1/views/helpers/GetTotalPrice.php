<?php
	class Zend_View_Helper_GetTotalPrice extends Zend_View_Helper_Abstract
	{
		public function getTotalPrice($id)
		{
			global $db;	
			$return = array();		
			$select = $db -> select();
			$select->from(array('p' => 'products')) 
		        	-> join (array('m' => 'marchants'),'p.merchant_id=m.id')
					->columns(array('p.price as product_price','p.currency as priceCurrency','m.vat as vatValue','m.shipping_cost as shippingCost','m.currency as shippingCurrency'))
					->where('p.id=?',$id);	
			$stmt = $db->query($select);
			$result = $stmt->fetch();			
						
			// order price
			$orderprice = (float)$result['product_price'];			
			$priceRate=$this->view->getCurrencyRate($result['priceCurrency'],'CHF');	
			$priceChf = $result['product_price']*$priceRate;
			
			//shipping cost
			if($result['shipping_cost_type'] == 'percent'){
			 // if shipping cost is in percent.
			 $shipping_currency = $result['priceCurrency'];
			 $shippingCost = $orderprice * (float)$result['shippingCost'] / 100;
			 if($shippingCost < $result['shipping_min'])$shippingCost = $result['shipping_min'];
			 
			 
			 
				 if($shippingCost > 0){
					$shippingChf = $shippingCost * $priceRate;
				 }else{
					$shippingChf = 0;
				 }
			}else{
			    $shipping_currency = $result['shippingCurrency'];
				$shippingCost = (float)$result['shippingCost'];
				$shippingRate=$this->view->getCurrencyRate($result['shippingCurrency'],'CHF');
				$shippingChf = $result['shippingCost']*$shippingRate;		
			}
			
			
			
			$pricePlusShipping = $priceChf + $shippingChf;
			$exchangeCost = ($pricePlusShipping * 1.5) / 100; // 1.5% exchange cost
			$return['pricePlusShippingPlusExchangeCost'] = $pricePlusShippingPlusExchangeCost = $pricePlusShipping + $exchangeCost;
			
			//vat calculation
			// if VAT <= 5 chf, VAT = 0; else VAT = (total oder value)*7.6%
			$vat = $pricePlusShippingPlusExchangeCost * (float)$result['vat'] / 100;
			//if($vat <= 5)$vat = 0;
			
			$return['vat'] = $vat;
			
			$customDuty = 0; // Zoll = 0
			$return['zoll'] = $customDuty;
			
			$return['swiss_postal_fee'] = $swiss_postal_fee = (float)$result['swiss_postal_fee'];
			

			$return['totalPrice'] =  array_sum($return); 
			$return['shipping_currency'] = $shipping_currency;
			$return['import_cost'] = $vat + $customDuty + $swiss_postal_fee;
			$return['shipping_cost'] = (float)$shippingCost;
			
			
			
			
			return $return; 
			
		}
	}
	
?>