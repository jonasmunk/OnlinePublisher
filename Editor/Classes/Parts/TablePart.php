<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['table'] = array(
	'fields' => array(
		'html' => array( 'type' => 'text' )
	)
);

class TablePart extends Part
{
	var $html;
	
	function TablePart() {
		parent::Part('table');
	}
	
	function load($id) {
		return Part::load('table',$id);
	}

	function setHtml($html) {
	    $this->html = $html;
	}

	function getHtml() {
	    return $this->html;
	}
	
}
?>