<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['project'] = array(
	'parentProjectId' => array('type'=>'int','column'=>'parent_project_id')
);
class Project extends Object {

	var $parentProjectId = 0;

	function Project() {
		parent::Object('project');
	}

	static function load($id) {
		return Object::get($id,'project');
	}

	function setParentProjectId($projectId) {
		$this->parentProjectId = $projectId;
	}

	function getParentProjectId() {
		return $this->parentProjectId;
	}
	
    /////////////////////////// Persistence ////////////////////////


	function sub_publish() {
		$data =
		'<project xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</project>';
		return $data;
	}

	function removeMore() {
		// Set parent of subProjects to parent of this project
		$sql = "update project set parent_project_id = ".Database::int($this->parentProjectId)." where parent_project_id=".Database::int($this->id);
		Database::delete($sql);
		// Move task to parent project (or no containing object)
		$sql = "update task set containing_object_id = ".Database::int($this->parentProjectId)." where containing_object_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
	//////////////////////// Convenience /////////////////////////
	
	function getPath($includeSelf=false) {
	    $output = array();
	    if ($includeSelf) {
	        $output[] = array('id' => $this->id, 'title' => $this->title);
	    }
	    $parent = $this->parentProjectId;
	    while ($parent>0) {
    	    $sql = "select object.id,object.title,project.parent_project_id from project,object where project.object_id = object.id and object.id=".Database::int($parent);
    	    if ($row=Database::selectFirst($sql)) {
    	        $output[] = array('id'=>$row['id'],'title'=>$row['title']);
    	        $parent = $row['parent_project_id'];
    	    } else {
    	        $parent = 0;
    	    }
	    }
	    return array_reverse($output);
	}
	
	function getSubProjectIds() {
		$ids = array();
		$this->_getSubProjectIdsSpider($this->id,$ids);
		return $ids;
	}
	
	function _getSubProjectIdsSpider($parent,&$ids) {
		$sql = "select object_id from project where parent_project_id=".$parent;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $ids[] = $row['object_id'];
			$this->_getSubProjectIdsSpider($row['object_id'],$ids);
		}
		Database::free($result);
	}
	
	function getSubProjects($filter=array()) {
		$output = array();
		
		$ids = array();
		if ($filter['includeSubProjects']==true) {
			$ids = $this->getSubProjectIds();
		}
		$ids[] = $this->id;
		
		$sql = "select object_id from project,object where project.object_id=object.id and parent_project_id in (".implode(",",$ids).") order by object.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $output[] = Project::load($row['object_id']);
		}
		return $output;  
	}
	
	function getSubTasks($filter=array()) {
		$output = array();
		$ids = array();
		if ($filter['includeSubProjects']==true) {
			$ids = $this->getSubProjectIds();
		}
		$ids[] = $this->id;
		
		$sql = "select object_id from task,object where task.object_id = object.id and containing_object_id in (".implode(",",$ids).")".
		(isset($filter['completed']) ? " and task.completed=".Database::boolean($filter['completed']) : "").
		" order by object.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $output[] = Task::load($row['object_id']);
		}
		Database::free($result);
		return $output;  
	}
	
	function getSubProblems($filter=array()) {
		$output = array();
		$ids = array();
		if ($filter['includeSubProjects']) {
			$ids = $this->getSubProjectIds();
		}
		$ids[] = $this->id;
		
		$sql = "select object_id from problem,object where problem.object_id = object.id and containing_object_id in (".implode(",",$ids).")".
		(isset($filter['completed']) ? " and problem.completed=".Database::boolean($filter['completed']) : "").
		" order by object.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
		    $output[] = Problem::load($row['object_id']);
		}
		return $output;  
	}
	
	function getMilestones($filter=array()) {
		
		$ids = array();
		if ($filter['includeSubProjects']==true) {
			$ids = $this->getSubProjectIds();
		}
		$ids[] = $this->id;
		$milestoneFilter = array('projects'=>$ids,'sort'=>'deadline');
		if (isset($filter['completed'])) {
			$milestoneFilter['completed'] = $filter['completed'];
		}
		return Milestone::search($milestoneFilter);
	}
	
	/**
	 * Returns the maximum deadline of all tasks and problems in the project.
	 * Only considering events in the future
	 * @return int Unix timestamp or 0 if no future event has a deadline
	 */
	function getMaxFutureDeadlineOfChildren() {
	    $maxTask = 0;
	    $maxProblem = 0; 
	    $sql = "select UNIX_TIMESTAMP(max(deadline)) as deadline from task where deadline>now() and containing_object_id=".$this->id;
	    if ($row=Database::selectFirst($sql)) {
	        $maxTask = $row['deadline'];
	    }
	    $sql = "select UNIX_TIMESTAMP(max(deadline)) as deadline from problem where deadline>now() and containing_object_id=".$this->id;
	    if ($row=Database::selectFirst($sql)) {
	        $maxProblem = $row['deadline'];
	    }
	    return max($maxTask,$maxProblem);
	}
	
	////////////////////////// UI-support ////////////////////////
	
	function optionSpider($prefix,$parent,$ignore) {
        $gui='';
        $sql="SELECT object.id,object.title FROM project,object WHERE object.id=project.object_id and project.parent_project_id=".$parent." order by title";
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            if ($row['id']!=$ignore) {
                $title = $prefix.Strings::shortenString($row['title'],20);
            	$gui.='<option title="'.Strings::escapeXML($title).'" value="'.$row['id'].'"/>'.
            	Project::optionSpider($prefix.'··',$row['id'],$ignore);
    	    }
        }
        Database::free($result);
        return $gui;
    }
    
}
?>