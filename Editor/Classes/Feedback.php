<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
global $basePath;
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['feedback'] = array(
	'name'   => array('type'=>'text'),
	'email'  => array('type'=>'text'),
	'message'  => array('type'=>'text')
);

class Feedback extends Object {
	var $name;
	var $email;
	var $message;
	
	function Feedback() {
		parent::Object('feedback');
	}
	
	function load($id) {
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
	
	function getIn2iGuiIcon() {
        return "common/object";
	}
}
?>