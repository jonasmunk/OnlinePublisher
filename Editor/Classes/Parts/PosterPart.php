<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['PosterPart'] = array(
	'table' => 'part_poster',
	'properties' => array(
		'recipe' => array( 'type' => 'text' )
	)
);

class PosterPart extends Part
{
	var $recipe;
	
	function PosterPart() {
		parent::Part('poster');
	}
	
	static function load($id) {
		return Part::get('poster',$id);
	}

	function setRecipe($recipe) {
	    $this->recipe = $recipe;
	}

	function getRecipe() {
	    return $this->recipe;
	}
}
?>