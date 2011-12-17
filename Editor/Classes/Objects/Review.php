<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');

Object::$schema['review'] = array(
	'accepted' => array('type'=>'boolean'),
	'date' => array('type'=>'datetime')
);
class Review extends Object {

    var $accepted;
	var $date;
    
    function Review() {
		parent::Object('review');
    }

	function load($id) {
		return Object::get($id,'review');
	}
	
	function setAccepted($accepted) {
	    $this->accepted = $accepted;
	}

	function getAccepted() {
	    return $this->accepted;
	}
	
	function setDate($date) {
	    $this->date = $date;
	}

	function getDate() {
	    return $this->date;
	}
	
}
?>