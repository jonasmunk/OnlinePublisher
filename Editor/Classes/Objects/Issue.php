<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Issue'] = array(
	'table' => 'issue',
	'properties' => array(
		'kind' => array('type'=>'string'),
		'statusId' => array('type'=>'int','column'=>'issuestatus_id','relation'=>array('class'=>'Issuestatus','property'=>'id'))
	)
);
Object::$schema['issue'] = array(
	'kind' => array('type'=>'string'),
	'statusId' => array('type'=>'int','column'=>'issuestatus_id')
);
class Issue extends Object {
    
	static $unknown = 'unknown';
	static $improvement = 'improvement';
	static $task = 'task';
	static $feedback = 'feedback';
	static $error = 'error';

    var $kind;
	var $statusId;
    
    function Issue() {
		parent::Object('issue');
    }

	function load($id) {
		return Object::get($id,'issue');
	}
	
	function setKind($kind) {
	    $this->kind = $kind;
	}

	function getKind() {
	    return $this->kind;
	}
	
	function setStatusId($statusId) {
	    $this->statusId = $statusId;
	}

	function getStatusId() {
	    return $this->statusId;
	}
	
	
	function getIcon() {
		return 'file/generic';
	}
}
?>