<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['RichtextPart'] = [
	'table' => 'part_richtext',
	'properties' => array(
		'html' => array( 'type' => 'string' )
	)
];

class RichtextPart extends Part
{
	var $html;
	
	function RichtextPart() {
		parent::Part('richtext');
	}
	
	static function load($id) {
		return Part::get('richtext',$id);
	}

	function setHtml($html) {
	    $this->html = $html;
	}

	function getHtml() {
	    return $this->html;
	}
	
}
?>