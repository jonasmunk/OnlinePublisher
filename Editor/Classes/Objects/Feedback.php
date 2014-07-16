<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Feedback'] = [
    'table' => 'feedback',
    'properties' => array(
    	'name'   => array('type'=>'string'),
    	'email'  => array('type'=>'string'),
    	'message'  => array('type'=>'string')
    )
];

class Feedback extends Object {
	var $name;
	var $email;
	var $message;
	
	function Feedback() {
		parent::Object('feedback');
	}
	
	static function load($id) {
		return Object::get($id,'feedback');
	}
	
	function setName($name) {
	    $this->name = $name;
	}

	function getName() {
	    return $this->name;
	}
	
	function setEmail($email) {
	    $this->email = $email;
	}

	function getEmail() {
	    return $this->email;
	}
	
	function setMessage($message) {
	    $this->message = $message;
	}

	function getMessage() {
	    return $this->message;
	}
	
	function sub_publish() {
		$data = '<feedback xmlns="'.parent::_buildnamespace('1.0').'">';
		$data.='</feedback>';
		return $data;
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
        return "common/object";
	}
}
?>