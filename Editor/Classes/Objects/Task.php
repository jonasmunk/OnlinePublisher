<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');

class Task extends Object {
	var $deadline;
	var $completed=false;
	var $containingObjectId=0;
	var $milestoneId=0;
	var $priority;

	function Task() {
		parent::Object('task');
	}

	function setDeadline($deadline) {
		$this->deadline = $deadline;
	}

	function getDeadline() {
		return $this->deadline;
	}

	function setCompleted($completed) {
		$this->completed = $completed;
	}

	function getCompleted() {
		return $this->completed;
	}

	function setContainingObjectId($id) {
		$this->containingObjectId = $id;
	}

	function getContainingObjectId() {
		return $this->containingObjectId;
	}

	function setMilestoneId($id) {
		$this->milestoneId = $id;
	}

	function getMilestoneId() {
		return $this->milestoneId;
	}
	
	function setPriority($priority) {
	    $this->priority = $priority;
	}

	function getPriority() {
	    return $this->priority;
	}
	

    /////////////////////////// Persistence ////////////////////////

	function load($id) {
		$sql = "select UNIX_TIMESTAMP(deadline) as deadline,containing_object_id,completed,milestone_id,priority from task where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new Task();
			$obj->_load($id);
			$obj->deadline=$row['deadline'];
			$obj->containingObjectId=$row['containing_object_id'];
			$obj->completed=($row['completed']==1);
			$obj->milestoneId=$row['milestone_id'];
			$obj->priority=$row['priority'];
			return $obj;
		}
		return null;
	}

	function sub_create() {
		$sql="insert into task (object_id,deadline,containing_object_id,milestone_id,completed,priority) values (".
		$this->id.
		",".Database::datetime($this->deadline).
		",".Database::int($this->containingObjectId).
		",".Database::int($this->milestoneId).
		",".Database::boolean($this->completed).
		",".Database::float($this->priority).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update task set ".
		"deadline=".Database::datetime($this->deadline).
		",containing_object_id=".Database::int($this->containingObjectId).
		",milestone_id=".Database::int($this->milestoneId).
		",completed=".Database::boolean($this->completed).
		",priority=".Database::float($this->priority).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<task xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</task>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from task where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Part/Generic';
	}
}
?>