<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class CSVWriter {
	
	var $dirty = false;
	
	function newLine() {
		echo "\n";
		$this->dirty = false;
		return $this;
	}
	
	function string($str) {
		if ($this->dirty) {
			echo ",";
		}
		echo "\"";
		echo $str;
		echo "\"";
		$this->dirty = true;
		return $this;
	}
	
	function date($date) {
		if ($this->dirty) {
			echo ",";
		}
		echo "\"";
		echo Dates::formatCSV($date);
		echo "\"";
		$this->dirty = true;
		return $this;
	}
}