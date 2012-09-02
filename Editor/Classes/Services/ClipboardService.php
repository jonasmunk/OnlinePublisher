<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ClipboardService {
	
	function copySection($id) {
		$_SESSION['core.clipboard'] = array(
			'action' => 'copy',
			'type' => 'section',
			'id' => $id
		);
	}

	function cutSection($id) {
		$_SESSION['core.clipboard'] = array(
			'action' => 'cut',
			'type' => 'section',
			'id' => $id
		);
	}
	
	function getClipboard() {
		return @$_SESSION['core.clipboard'];
	}
	
	function clear() {
		$_SESSION['core.clipboard'] = null;
	}
}