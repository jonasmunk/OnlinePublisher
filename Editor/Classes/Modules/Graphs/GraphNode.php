<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Graphs
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class GraphNode {
	var $id;
	var $icon;
	var $label;
	
	function GraphNode($id,$label,$icon) {
		$this->id = $id;
		$this->label = $label;
		$this->icon = $icon;
	}
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setIcon($icon) {
	    $this->icon = $icon;
	}

	function getIcon() {
	    return $this->icon;
	}
	
	function setLabel($label) {
	    $this->label = $label;
	}

	function getLabel() {
	    return $this->label;
	}
	
	function equals($node) {
		return $node->getId()==$this->id;
	}
}