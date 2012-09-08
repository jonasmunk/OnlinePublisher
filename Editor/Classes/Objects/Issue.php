<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['issue'] = array(
	'kind' => array('type'=>'string')
);
class Issue extends Object {
    
	static $unknown = 'unknown';
	static $improvement = 'improvement';
	static $task = 'task';
	static $feedback = 'feedback';
	static $error = 'error';

    var $kind;
    
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
	
	
	
	function getIcon() {
		return 'file/generic';
	}
}
?>