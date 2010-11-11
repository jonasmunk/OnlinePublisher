<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */

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