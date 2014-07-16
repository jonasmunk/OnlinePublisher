<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Personrole'] = [
	'table' => 'personrole',
	'properties' => [
    	'personId' => ['type' => 'int', 'column' => 'person_id', 'relation' => 
            ['class' => 'Person', 'property' => 'id']
        ]
	]
];

class Personrole extends Object {
	
	var $personId;
	
	function Personrole() {
		parent::Object('personrole');
	}
	
	static function load($id) {
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