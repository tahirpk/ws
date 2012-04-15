<?php
	class Zend_View_Helper_GetSefUrl
	{
		public function getSefUrl($txtTitle,$tableName='')
		{
			
			$germanTranslation = array
									(
										"ü"=>"ue",
										"ö"=>"oe",
										"ä"=>"ae",
										"ß"=>"ss",
										"Ä"=>"Ae",
										"Ö"=>"Oe",
										"Ü"=>"Ue",
										"é"=>"e",
										"è"=>"e",
										"á"=>"a",
										"à"=>"a",
										"É"=>"E" 
										
									);
			$urlSafeTitle = strtr($txtTitle,$germanTranslation);
			
			$seoTitle = preg_replace(array("/^\W*|\W*$/","/\W|_/","/[\s]+/"), array('',' ','-'), strtolower(trim($urlSafeTitle)));
			
			$seoTitle = substr($seoTitle,0,240);
			
			return $seoTitle;
		}
	}
	
?>