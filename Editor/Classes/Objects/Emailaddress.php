<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Emailaddress'] = [
    'table' => 'emailaddress',
    'properties' => array(
    	'address' => array('type'=>'string'),
    	'containingObjectId'   => array('type'=>'int','column'=>'containing_object_id')
    )
];

class Emailaddress extends Object {
	var $address;
	var $containingObjectId=0;

	function Emailaddress() {
		parent::Object('emailaddress');
	}

	static function load($id) {
		return Object::get($id,'emailaddress');
	}
	
	function setAddress($address) {
		$this->title = $address;
		$this->address = $address;
	}

	function getAddress() {
		return $this->address;
	}

	function setContainingObjectId($id) {
		$this->containingObjectId = $id;
	}

	function getContainingObjectId() {
		return $this->containingObjectId;
	}	

    /////////////////////////// Persistence ////////////////////////

	function sub_publish() {
		$data =
		'<emailaddress xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</emailaddress>';
		return $data;
	}
	
	/////////////////////////// GUI /////////////////////////
		
	function getIcon() {
		return "common/email";
	}
}
?>