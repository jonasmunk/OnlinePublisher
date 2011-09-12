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

Part::$schema['richtext'] = array(
	'fields' => array(
		'html' => array( 'type' => 'text' )
	)
);

class RichtextPart extends Part
{
	var $html;
	
	function RichtextPart() {
		parent::Part('richtext');
	}
	
	function load($id) {
		return Part::load('richtext',$id);
	}

	function setHtml($html) {
	    $this->html = $html;
	}

	function getHtml() {
	    return $this->html;
	}
	
}
?>