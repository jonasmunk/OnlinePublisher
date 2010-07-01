<?
require_once $basePath.'Editor/Include/Session.php';

class NewsController {
	
	function getGroupId() {
		return getToolSessionVar('news','group',-1);
	}
	
	function setGroupId($id) {
		if ($id!=NewsController::getGroupId()) {
			NewsController::setUpdateHierarchy(true);
		}
		setToolSessionVar('news','group',$id);
	}

	function getViewType() {
		return getToolSessionVar('news','viewtype','all');
	}
	
	function setViewType($value) {
		if ($value!=NewsController::getViewType()) {
			NewsController::setUpdateHierarchy(true);
		}
		setToolSessionVar('news','viewtype',$value);
	}

	function getViewMode() {
		return getToolSessionVar('news','viewmode','List');
	}
	
	function setViewMode($value) {
		if (strlen($value)>0) {
			setToolSessionVar('news','viewmode',$value);
		}
	}

	function getListGrouping() {
		return getToolSessionVar('news','listgrouping','none');
	}
	
	function setListGrouping($value) {
		if (strlen($value)>0) {
			setToolSessionVar('news','listgrouping',$value);
		}
	}

	function getListState() {
		return getToolSessionVar('news','liststate','all');
	}
	
	function setListState($value) {
		if (strlen($value)>0) {
			setToolSessionVar('news','liststate',$value);
		}
	}
	
	function getGroupingOpen($type) {
		$groupings = getToolSessionVar('news','groupingOpen',array());
		if (isset($groupings[$type])) {
			return $groupings[$type];
		} else {
			return true;
		}
	}
	
	function setGroupingOpen($type,$open) {
		$groupings = getToolSessionVar('news','groupingOpen',array());
		$groupings[$type] = $open;
		setToolSessionVar('news','groupingOpen',$groupings);
	}
	
	function setUpdateHierarchy($value) {
		setToolSessionVar('news','updateHierarchy',$value);
	}

	function getUpdateHierarchy() {
		return getToolSessionVar('news','updateHierarchy',false);
	}
	
	function getBaseWindow() {
		$type = NewsController::getViewType();
		if ($type=='group') {
			return 'Group.php';
		} elseif ($type=='groups') {
			return 'Groups.php';
		} else {
			return 'Library.php';
		}
	}
}
?>