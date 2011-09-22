<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Graphs
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Graph {
	var $nodes = array();
	var $edges = array();
	
	function getNodes() {
	    return $this->nodes;
	}

	function getEdges() {
	    return $this->edges;
	}

	function addNode($node) {
		if (!$this->hasNode($node)) {
			$this->nodes[] = $node;
		}
	}
	
	function addEdge($from,$to) {
		if ($this->isConnected($from,$to)) {
			return;
		}
		$fromFound = false;
		$toFound = false;
		foreach ($this->nodes as $node) {
			if ($node->getId()===$from) {
				$fromFound=true;
			}
			if ($node->getId()===$to) {
				$toFound=true;
			}
		}
		if ($fromFound && $toFound) {
			$this->edges[] = array('from'=>$from,'to'=>$to);
		}
	}
	
	function isConnected($from,$to) {
		foreach ($this->edges as $edge) {
			if ($edge['from']===$from && $edge['to']===$to) {
				return true;
			}
			if ($edge['to']===$from && $edge['from']===$to) {
				return true;
			}
		}
		return false;
	}
	
	function hasNode($node) {
		foreach ($this->nodes as $existing) {
			if ($existing->equals($node)) {
				return true;
			}
		}
		return false;
	}
}