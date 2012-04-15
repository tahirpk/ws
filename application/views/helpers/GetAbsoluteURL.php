<?php
	class Zend_View_Helper_GetAbsoluteURL
	{
		public function getAbsoluteURL()
		{		
			 $pageURL = 'http';
			 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "on") { $pageURL = "http"; }
			 $pageURL .= "://";
			 if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
			 } else {
			  $pageURL .= $_SERVER["SERVER_NAME"];
			 }
			 return $pageURL;
		}
	}
?>