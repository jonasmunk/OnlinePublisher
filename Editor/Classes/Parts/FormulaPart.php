<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['FormulaPart'] = [
	'table' => 'part_formula',
	'properties' => [
		'receiverName'   => ['type'=>'text','column'=>'receivername'],
		'receiverEmail'   => ['type'=>'text','column'=>'receiveremail'],
		'recipe' => ['type' => 'text']
	]
];

class FormulaPart extends Part
{
	var $receiverName;
	var $receiverEmail;
	var $recipe;
	
	function FormulaPart() {
		parent::Part('formula');
	}
	
	static function load($id) {
		return Part::get('formula',$id);
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