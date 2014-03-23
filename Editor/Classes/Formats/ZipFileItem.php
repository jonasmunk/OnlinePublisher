<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ZipFileItem {
	
	var $info = null;
	var $delegate = null;
	
	function ZipFileItem(&$info,&$delegate) {
		$this->info = $info;
		$this->delegate = $delegate;
	}
	
	function extract() {
		global $basePath;
		$extracted = $this->delegate->extractByIndex($this->info['index'],$basePath.'local/cache/temp');
		if ($extracted[0]['status']=='ok') {
			return $extracted[0]['filename'];
		}
		return null;
	}
}