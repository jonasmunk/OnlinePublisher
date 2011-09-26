<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Core
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class PageQuery {
	
	private $text;
	private $ordering = array();
	private $direction = 'ascending';
	private $windowPage = 0;
	private $windowSize = 100;
	
	function rows() {
		return new PageQuery();
	}
	
	function withText($text) {
		$this->text = $text;
		return $this;
	}
	
	function withWindowPage($page) {
		$this->windowPage = $page;
		return $this;
	}
	
	function withWindowSize($size) {
		$this->windowSize = $size;
		return $this;
	}
	
	function orderBy($order) {
		if (StringUtils::isNotBlank($order)) {
			$this->ordering[] = $order;
		}
		return $this;
	}
	
	function ascending() {
		$this->direction = 'ascending';
		return $this;
	}
	
	function descending() {
		$this->direction = 'descending';
		return $this;
	}
	
	function withDirection($direction) {
		$this->direction = $direction=='ascending' ? 'ascending' : 'descending';
		return $this;
	}
	
	function getText() {
		return $this->text;
	}
	
	function getWindowSize() {
		return $this->windowSize;
	}
	
	function getWindowPage() {
		return $this->windowPage;
	}
	
	function getDirection() {
		return $this->direction;
	}
	
	function getOrdering() {
		return $this->ordering;
	}
	
	function search() {
		return PageService::search($this);
	}
}