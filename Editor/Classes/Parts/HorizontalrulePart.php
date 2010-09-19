<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['horizontalrule'] = array(
	'fields' => array(
	)
);

class HorizontalrulePart extends Part
{
	function HorizontalrulePart() {
		parent::Part('horizontalrule');
	}
	
	function load($id) {
		return Part::load('horizontalrule',$id);
	}
}
?>