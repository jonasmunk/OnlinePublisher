<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['TablePart'] = array(
	'table' => 'part_table',
	'properties' => array(
		'html' => array( 'type' => 'text' )
	)
);

class TablePart extends Part
{
	var $html;
	
	function TablePart() {
		parent::Part('table');
	}
	
	static function load($id) {
		return Part::get('table',$id);
	}

	function setHtml($html) {
	    $this->html = $html;
	}

	function getHtml() {
	    return $this->html;
	}
	
}
?>