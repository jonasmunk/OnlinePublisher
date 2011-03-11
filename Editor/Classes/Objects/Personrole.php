<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['personrole'] = array(
	'personId'   => array('type'=>'int','column'=>'person_id')
);
class Personrole extends Object {
	
	var $personId;
	
	function Personrole() {
		parent::Object('personrole');
	}
	
	function load($id) {
		return Object::get($id,'personrole');
	}

	function setPersonId($personId) {
		$this->personId = $personId;
	}
	
	function getPersonId() {
		return $this->personId;
	}
	
}
?>