<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['HtmlPart'] = [
	'table' => 'part_html',
	'properties' => [
		'html' => [ 'type' => 'text' ]
	]
];

class HtmlPart extends Part
{
	var $html;
	
	function HtmlPart() {
		parent::Part('html');
	}
	
	static function load($id) {
		return Part::get('html',$id);
	}

	function setHtml($html) {
	    $this->html = $html;
	}

	function getHtml() {
	    return $this->html;
	}
	
}
?>