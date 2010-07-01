<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Milestone extends Object {
	var $deadline;
	var $containingObjectId;
	var $completed;

	function Milestone() {
		parent::Object('milestone');
	}

	function setDeadline($deadline) {
		$this->deadline = $deadline;
	}

	function getDeadline() {
		return $this->deadline;
	}

	function setContainingObjectId($id) {
		$this->containingObjectId = $id;
	}

	function getContainingObjectId() {
		return $this->containingObjectId;
	}

	function setCompleted($completed) {
		$this->completed = $completed;
	}

	function getCompleted() {
		return $this->completed;
	}

    /////////////////////////// Persistence ////////////////////////

	function load($id) {
		$obj = new Milestone();
		$obj->_load($id);
		$sql = "select UNIX_TIMESTAMP(deadline) as deadline,containing_object_id,completed".
		" from milestone where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->_populate($row);
		}
		return $obj;
	}
	
	function _fixOptions(&$arr) {
		if (!is_array($arr)) $arr = array();
		if (!isset($arr['sort'])) $arr['sort'] = 'title';
		if (!isset($arr['project'])) $arr['project'] = 0;
		if (!isset($arr['projects'])) $arr['projects'] = array();
	}
	
	function search($options = null) {
		Milestone::_fixOptions($options);
		$sql = "select object.id from milestone,object where object.id=milestone.object_id";
		if ($options['project']>0) {
			$sql.=" and containing_object_id=".$options['project'];
		} elseif (count($options['projects'])>0) {
			$sql.=" and containing_object_id in (".implode(",",$options['projects']).")";
		}
		if (isset($options['completed'])) {
			$sql.=" and milestone.completed=".sqlBoolean($options['completed']);
		}
		if ($options['sort'] == 'deadline') {
			$sql.=' order by deadline';
		} else {
			$sql.=' order by object.title';
		}
		$result = Database::select($sql);
		$ids = array();
		while ($row = Database::next($result)) {
			$ids[] = $row['id'];
		}
		Database::free($result);
		
		$list = array();
		foreach ($ids as $id) {
			$list[] = Milestone::load($id);
		}
		return $list;
	}

	function _populate(&$row) {
		$this->deadline=$row['deadline'];
		$this->containingObjectId=$row['containing_object_id'];
		$this->completed=$row['completed']==1;
	}

	function sub_create() {
		$sql="insert into milestone (object_id,deadline,containing_object_id,completed) values (".
		$this->id.
		",".sqlTimestamp($this->deadline).
		",".sqlInt($this->containingObjectId).
		",".sqlBoolean($this->completed).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update milestone set ".
		"deadline=".sqlTimestamp($this->deadline).
		",containing_object_id=".sqlInt($this->containingObjectId).
		",completed=".sqlBoolean($this->completed).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<milestone xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</milestone>';
		return $data;
	}

	function sub_remove() {
		$sql = "update task set milestone_id=0 where milestone_id=".$this->id;
		Database::update($sql);
		$sql = "delete from milestone where object_id=".$this->id;
		Database::delete($sql);
	}
	
	////////////////////// Convenience //////////////////////
	
	
	function getTasks() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Task.php');
		$output = array();
		$sql = "select object_id from task,object where task.object_id = object.id and task.milestone_id=".$this->id." order by object.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $output[] = Task::load($row['object_id']);
		}
		Database::free($result);
		return $output;
	}
	
	function getProblems() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Problem.php');
		$output = array();
		$sql = "select object_id from problem,object where problem.object_id = object.id and problem.milestone_id=".$this->id." order by object.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $output[] = Problem::load($row['object_id']);
		}
		Database::free($result);
		return $output;
	}
	
	function getCompletedInfo() {
		$output = array('completed' => 0, 'active' => 0);
		$sql = "select count(object_id)-sum(problem.completed) as active,sum(problem.completed) as completed from problem where problem.milestone_id=".$this->id." union select count(object_id)-sum(task.completed) as active,sum(task.completed) as completed from task where task.milestone_id=".$this->id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $output['completed']+=$row['completed'];
		    $output['active']+=$row['active'];
		}
		Database::free($result);
		return $output;
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Basic/Time';
	}
}
?>