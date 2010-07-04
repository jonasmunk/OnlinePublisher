<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Problem extends Object {
	var $deadline;
	var $completed=false;
	var $containingObjectId=0;
	var $milestoneId;
	var $priority;

	function Problem() {
		parent::Object('problem');
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

	function setMilestoneId($milestoneId) {
	    $this->milestoneId = $milestoneId;
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
		$obj = new Problem();
		$obj->_load($id);
		$sql = "select UNIX_TIMESTAMP(deadline) as deadline,containing_object_id,completed,milestone_id,priority".
		" from problem where object_id=".$id;
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
		$sql="insert into problem (object_id,deadline,containing_object_id,completed,milestone_id,priority) values (".
		$this->id.
		",".Database::datetime($this->deadline).
		",".Database::int($this->containingObjectId).
		",".Database::boolean($this->completed).
		",".Database::int($this->milestoneId).
		",".Database::float($this->priority).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update problem set ".
		"deadline=".Database::datetime($this->deadline).
		",containing_object_id=".Database::int($this->containingObjectId).
		",completed=".Database::boolean($this->completed).
		",milestone_id=".Database::int($this->milestoneId).
		",priority=".Database::float($this->priority).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<problem xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</problem>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from problem where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Basic/Stop';
	}
}
?>