<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['formula'] = array(
	'fields' => array(
		'receiverName'   => array('type'=>'text','column'=>'receivername'),
		'receiverEmail'   => array('type'=>'text','column'=>'receiveremail')
	)
);
class FormulaPart extends Part
{
	var $receiverName;
	var $receiverEmail;
	
	function FormulaPart() {
		parent::Part('formula');
	}
	
	function load($id) {
		return Part::load('formula',$id);
	}
	
	function setReceiverName($receiverName) {
	    $this->receiverName = $receiverName;
	}

	function getReceiverName() {
	    return $this->receiverName;
	}
	
	function setReceiverEmail($receiverEmail) {
	    $this->receiverEmail = $receiverEmail;
	}

	function getReceiverEmail() {
	    return $this->receiverEmail;
	}
	
}
?>