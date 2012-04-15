<?php
  class Zend_Controller_Action_Helper_AdminPaginator extends Zend_Controller_Action_Helper_Abstract
  {
    public function direct()
    {
        return 'Call _getPaginatorData() method';
    }
	
	function _getPaginatorData($params, $perPage, $selectObj = NULL, $count) {
		$session = new Zend_Session_Namespace('Category_Search');
		$dbSelectPaginator = new Zend_Paginator_Adapter_DbSelect($selectObj);
		$dbSelectPaginator->setRowCount((int)$count);
		$paginator = new Zend_Paginator($dbSelectPaginator);
		$paginator->setPageRange(5);
		$paginator->setCurrentPageNumber($params);
		if($perPage == 'All')
		{
			$paginator->setItemCountPerPage($count);
		}
		elseif($perPage != '')
		{
			$paginator->setItemCountPerPage($perPage);
		}
		elseif($session->perPage!='')
		{
			$paginator->setItemCountPerPage($session->perPage);

		}
		else
		{
			$paginator->setItemCountPerPage(50);

		}
		return $paginator;
	}
	
  }
?>