<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['HorizontalrulePart'] = [
	'table' => 'part_horizontalrule',
	'properties' => []
];

class HorizontalrulePart extends Part
{
	function HorizontalrulePart() {
		parent::Part('horizontalrule');
	}
	
	static function load($id) {
		return Part::get('horizontalrule',$id);
	}
}
?>