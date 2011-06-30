<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['emailaddress'] = array(
	'address' => array('type'=>'string'),
	'containingObjectId'   => array('type'=>'int','column'=>'containing_object_id')
);
class EmailAddress extends Object {
	var $address;
	var $containingObjectId=0;

	function EmailAddress() {
		parent::Object('emailaddress');
	}

	function load($id) {
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
	    return 'Element/EmailAddress';
	}
	
	function getIn2iGuiIcon() {
		return "common/email";
	}
}
?>