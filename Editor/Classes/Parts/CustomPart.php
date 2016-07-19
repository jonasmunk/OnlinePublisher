<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['CustomPart'] = [
	'table' => 'part_custom',
	'properties' => [
		'workflowId' => [ 'type' => 'int', 'column' => 'workflow_id', 'relation' => ['class' => 'Workflow','property' => 'id'] ],
		'viewId' => [ 'type' => 'int', 'column' => 'view_id', 'relation' => ['class' => 'View','property' => 'id'] ]
	]
];

class CustomPart extends Part
{
	var $workflowId;
	var $viewId;

	function CustomPart() {
		parent::Part('custom');
	}

	static function load($id) {
		return Part::get('custom',$id);
	}

	function setWorkflowId($workflowId) {
    $this->workflowId = $workflowId;
	}

	function getWorkflowId() {
    return $this->workflowId;
	}

  function setViewId($viewId) {
    $this->viewId = $viewId;
  }

  function getViewId() {
    return $this->viewId;
  }

}
?>