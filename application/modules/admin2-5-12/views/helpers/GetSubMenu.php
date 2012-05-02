<?php
class Admin_View_Helper_GetSubMenu
{
	public function getSubMenu($parent_id) 
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		if($parent_id > 0){
		}else{
		   $parent_id = -1;
		}
		$select = $db->select()
						->from('modules')
						->where('parent_id = ?', $parent_id)
						->order('sort_id');
						
		$result = $db->fetchAll($select);
		
		return $result;
	}
}
?>