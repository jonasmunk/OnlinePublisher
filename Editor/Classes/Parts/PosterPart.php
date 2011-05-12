<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['poster'] = array(
	'fields' => array(
		'recipe' => array( 'type' => 'text' )
	)
);

class PosterPart extends Part
{
	var $recipe;
	
	function PosterPart() {
		parent::Part('poster');
	}
	
	function load($id) {
		return Part::load('poster',$id);
	}

	function setRecipe($recipe) {
	    $this->recipe = $recipe;
	}

	function getRecipe() {
	    return $this->recipe;
	}
}
?>