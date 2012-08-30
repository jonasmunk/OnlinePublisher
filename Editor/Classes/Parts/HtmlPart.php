<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['html'] = array(
	'fields' => array(
		'html' => array( 'type' => 'text' )
	)
);

class HtmlPart extends Part
{
	var $html;
	
	function HtmlPart() {
		parent::Part('html');
	}
	
	function load($id) {
		return Part::load('html',$id);
	}

	function setHtml($html) {
	    $this->html = $html;
	}

	function getHtml() {
	    return $this->html;
	}
	
}
?>