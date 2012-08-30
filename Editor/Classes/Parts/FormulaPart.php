<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['formula'] = array(
	'fields' => array(
		'receiverName'   => array('type'=>'text','column'=>'receivername'),
		'receiverEmail'   => array('type'=>'text','column'=>'receiveremail'),
		'recipe' => array( 'type' => 'text' )
	)
);
class FormulaPart extends Part
{
	var $receiverName;
	var $receiverEmail;
	var $recipe;
	
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
	
	function setRecipe($recipe) {
	    $this->recipe = $recipe;
	}

	function getRecipe() {
	    return $this->recipe;
	}
	
}
?>