<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Task extends Object {
	var $deadline;
	var $completed=false;
	var $containingObjectId=0;
	var $milestoneId=0;

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
		$obj = new Task();
		$obj->_load($id);
		$sql = "select UNIX_TIMESTAMP(deadline) as deadline,containing_object_id,completed,milestone_id,priority from task where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->deadline=$row['deadline'];
			$obj->containingObjectId=$row['containing_object_id'];
			$obj->completed=($row['completed']==1);
			$obj->milestoneId=$row['milestone_id'];
			$obj->priority=$row['priority'];
		}
		return $obj;
	}

	function sub_create() {
		$sql="insert into task (object_id,deadline,containing_object_id,milestone_id,completed,priority) values (".
		$this->id.
		",".sqlTimestamp($this->deadline).
		",".sqlInt($this->containingObjectId).
		",".sqlInt($this->milestoneId).
		",".sqlBoolean($this->completed).
		",".sqlFloat($this->priority).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update task set ".
		"deadline=".sqlTimestamp($this->deadline).
		",containing_object_id=".sqlInt($this->containingObjectId).
		",milestone_id=".sqlInt($this->milestoneId).
		",completed=".sqlBoolean($this->completed).
		",priority=".sqlFloat($this->priority).
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