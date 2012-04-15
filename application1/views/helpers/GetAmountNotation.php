<?php
	class Zend_View_Helper_GetAmountNotation
	{
		public function getAmountNotation($currency,$country='DE',$points=2)
		{
		    $country = strtoupper($country);
			switch($country){
			   case 'DE':
			     return number_format((float)$currency,$points,',','.');
			   default:
			     return $currency;
			   break;
			}
			
		}
	}
	
?>