<?php
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once $basePath.'Editor/Classes/Core/InternalSession.php';

class PagesController {
	
	function getGroupView() {
		return InternalSession::getToolSessionVar('pages','groupview','none');
	}
	
	function setGroupView($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('pages','groupview',$value);
		}
	}

	function getViewDetails() {
		return InternalSession::getToolSessionVar('pages','viewdetails','simple');
	}
	
	function setViewDetails($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('pages','viewdetails',$value);
		}
	}
	
	function setActiveItem($type,$id=0) {
		InternalSession::setToolSessionVar('pages','activeitemtype',$type);
		InternalSession::setToolSessionVar('pages','activeitemid',$id);
	}
	
	function getActiveItem() {
		$type = InternalSession::getToolSessionVar('pages','activeitemtype');
		$id = InternalSession::getToolSessionVar('pages','activeitemid');
		if ($type) {
			return array('type' => $type, 'id' => $id);
		} else {
			return array('type' => 'allpages', 'id' => $id);
		}
	}
	
	/////////////////////////////// Search ///////////////////////////
		
	function setSearchPair($key,$value) {
		$_SESSION['tools.pages.searchPair']=array($key,$value);
	}

	function getSearchPair() {
		if (isset($_SESSION['tools.pages.searchPair'])) {
			return $_SESSION['tools.pages.searchPair'];
		}
		else {
			return array('','');
		}
	}
	
	////////////////////////////// New Page /////////////////////////

	function getNewPageInfo() {
		if (isset($_SESSION['tools.pages.newPage'])) {
			return $_SESSION['tools.pages.newPage'];
		}
		else {
			return getEmptyNewPageInfo();
		}
	}

	function setNewPageInfo($value) {
		$_SESSION['tools.pages.newPage']=$value;
	}

	function resetNewPageInfo() {
		$_SESSION['tools.pages.newPage']=PagesController::getEmptyNewPageInfo();
	}

	function getEmptyNewPageInfo() {
		return array("template" => 0,"design" => 0,"frame" => 0,"hierarchy" => 0,"hierarchyParent" => 0, "fixedHierarchy" => 0, "fixedHierarchyParent" => 0);
	}
}
?>