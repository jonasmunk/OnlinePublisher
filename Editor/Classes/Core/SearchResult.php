<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Core
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class SearchResult {
	
	private $list = array();
	private $total = 0;
   	private $windowPage = 0;
    private $windowSize = 20;

	function setList($list) {
	    $this->list = $list;
	}

	function getList() {
	    return $this->list;
	}
		
	function setTotal($total) {
	    $this->total = $total;
	}

	function getTotal() {
	    return $this->total;
	}
	
	function setWindowPage($windowPage) {
	    $this->windowPage = $windowPage;
	}

	function getWindowPage() {
	    return $this->windowPage;
	}
	
	function setWindowSize($windowSize) {
	    $this->windowSize = $windowSize;
	}

	function getWindowSize() {
	    return $this->windowSize;
	}
	
}