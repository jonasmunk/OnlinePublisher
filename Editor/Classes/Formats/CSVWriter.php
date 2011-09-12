<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');

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
		echo DateUtils::formatCSV($date);
		echo "\"";
		$this->dirty = true;
		return $this;
	}
}