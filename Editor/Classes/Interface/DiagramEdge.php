<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class DiagramEdge {

	var $from;
	var $to;
	var $label;
	var $color;

	function from($from) {
	    $this->from = $from;
		return $this;
	}

	function to($to) {
	    $this->to = $to;
		return $this;
	}
	
	function withLabel($label) {
	    $this->label = $label;
		return $this;
	}
	
	function withColor($color) {
	    $this->color = $color;
		return $this;
	}
	
}
?>