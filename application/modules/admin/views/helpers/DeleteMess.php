<?php
class Admin_View_Helper_DeleteMess
{
	public function DeleteMess($modname, $dataname)
	{
		return "Wollen Sie löschen ".$modname."(".$dataname.")?"; 
	}
	
}
?>