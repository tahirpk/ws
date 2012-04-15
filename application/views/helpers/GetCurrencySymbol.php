<?php
	class Zend_View_Helper_GetCurrencySymbol
	{
		public function getCurrencySymbol($currency)
		{
		    $currency = strtoupper($currency);
			switch($currency){
			   case 'USD':
			     return '$';
			   case 'GBP':
			     return '£';	 
			   default:
			     return $currency;
			   break;
			}
			
		}
	}
	
?>