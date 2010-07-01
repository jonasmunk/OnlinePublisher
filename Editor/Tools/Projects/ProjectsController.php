<?
require_once $basePath.'Editor/Include/Session.php';
require_once $basePath.'Editor/Classes/Settings.php';

class ProjectsController {
	
	function getGroupView() {
		return getToolSessionVar('projects','groupview','type');
	}
	
	function setGroupView($value) {
		if (strlen($value)>0) {
			setToolSessionVar('projects','groupview',$value);
		}
	}

	function getTimeView() {
		return getToolSessionVar('projects','timeview','always');
	}
	
	function setTimeView($value) {
		if (strlen($value)>0) {
			setToolSessionVar('projects','timeview',$value);
		}
	}

	function getProjectScope() {
		return getToolSessionVar('projects','projectscope','onlythisproject');
	}
	
	function setProjectScope($value) {
		if (strlen($value)>0) {
			setToolSessionVar('projects','projectscope',$value);
		}
	}

	function getProjectListState() {
		return getToolSessionVar('projects','projectliststate','any');
	}
	
	function setProjectListState($value) {
		if (strlen($value)>0) {
			setToolSessionVar('projects','projectliststate',$value);
		}
	}

	function getMilstoneGrouping() {
		return getToolSessionVar('projects','milestonegrouping','type');
	}
	
	function setMilstoneGrouping($value) {
		if (strlen($value)>0) {
			setToolSessionVar('projects','milestonegrouping',$value);
		}
	}
	
	function getGroupingOpen($type) {
		$groupings = getToolSessionVar('projects','groupingOpen',array());
		if (isset($groupings[$type])) {
			return $groupings[$type];
		} else {
			return true;
		}
	}
	
	function setGroupingOpen($type,$open) {
		$groupings = getToolSessionVar('projects','groupingOpen',array());
		$groupings[$type] = $open;
		setToolSessionVar('projects','groupingOpen',$groupings);
	}
}
?>