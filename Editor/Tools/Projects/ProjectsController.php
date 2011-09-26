<?
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once $basePath.'Editor/Classes/Core/InternalSession.php';

class ProjectsController {
	
	function getGroupView() {
		return InternalSession::getToolSessionVar('projects','groupview','type');
	}
	
	function setGroupView($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('projects','groupview',$value);
		}
	}

	function getTimeView() {
		return InternalSession::getToolSessionVar('projects','timeview','always');
	}
	
	function setTimeView($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('projects','timeview',$value);
		}
	}

	function getProjectScope() {
		return InternalSession::getToolSessionVar('projects','projectscope','onlythisproject');
	}
	
	function setProjectScope($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('projects','projectscope',$value);
		}
	}

	function getProjectListState() {
		return InternalSession::getToolSessionVar('projects','projectliststate','any');
	}
	
	function setProjectListState($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('projects','projectliststate',$value);
		}
	}

	function getMilstoneGrouping() {
		return InternalSession::getToolSessionVar('projects','milestonegrouping','type');
	}
	
	function setMilstoneGrouping($value) {
		if (strlen($value)>0) {
			InternalSession::setToolSessionVar('projects','milestonegrouping',$value);
		}
	}
	
	function getGroupingOpen($type) {
		$groupings = InternalSession::getToolSessionVar('projects','groupingOpen',array());
		if (isset($groupings[$type])) {
			return $groupings[$type];
		} else {
			return true;
		}
	}
	
	function setGroupingOpen($type,$open) {
		$groupings = InternalSession::getToolSessionVar('projects','groupingOpen',array());
		$groupings[$type] = $open;
		InternalSession::setToolSessionVar('projects','groupingOpen',$groupings);
	}
}
?>