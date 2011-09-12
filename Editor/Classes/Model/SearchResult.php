<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class SearchResult {
	
	private $list;
	private $total;
   	private $windowPage;
    private $windowSize;

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