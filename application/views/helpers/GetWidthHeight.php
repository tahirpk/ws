<?php
	class Zend_View_Helper_GetWidthHeight
	{
		public function getWidthHeight($width,$height)
		{
				if ($width > $height)
				{
					$factor = (float)$this->new_width / (float)$width;
				}
				else
				{
					$factor = (float)$this->new_height / (float)$height;
				}
				
				$new_height = $factor * $height;
				$new_width = $factor * $width;
				
				return array('width' => $new_width, 'height' => $new_height);
		}
	}









