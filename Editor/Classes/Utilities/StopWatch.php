<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class StopWatch {

	private $start = null;
	private $latest = null;
	private $end = null;
	
	function StopWatch() {
		$this->start = microtime(true);
		$this->latest = microtime(true);
	}
	
	function log($msg) {
		$x = microtime(true);
		Log::debug($msg.' : '.($x - $this->latest));
		$this->latest = $x;
	}

	function end() {
		$this->end = microtime(true);
	}

	function getTime() {
		return $this->end - $this->start;
	}
}
?>