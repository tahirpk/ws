<?php
class Admin_View_Helper_GetTopMenu
{
	public function getTopMenu()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
						->from('modules')
						->where("parent_id = '0' AND modules_status = '1'")
						->order('sort_id');
		$result = $db->fetchAll($select);
		return $result;
	}

}
?>