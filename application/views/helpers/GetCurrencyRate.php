<?php
   class Zend_View_Helper_GetCurrencyRate
	{
	    static $rates = array();
		public function getCurrencyRate($from,$to)
		{  
		    return 1;
		        $to = strtoupper($to);
				$from = strtoupper($from);
				
				if(isset(self::$rates[$from][$to])){
				   $rate = self::$rates[$from][$to];
				}else{
				
				$page = file('http://www.xe.net/ucc/convert.cgi?Amount=1&From=' . $from . '&To=' . $to);
				$match = array();
			
				preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/i', implode('', $page), $match);
			   
			    if (sizeof($match) > 0) {
				  self::$rates[$from][$to] = $match[1];
				  return $match[1];
				}else{
				  self::$rates[$from][$to] = 0;
				  return 0;
				}
			   }
			
			return self::$rates[$from][$to];
			
		}
		
		
	}
	
?>