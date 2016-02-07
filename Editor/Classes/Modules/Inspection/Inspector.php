<?php
/**
 * @package OnlinePublisher
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
interface Inspector {
	
	function inspect();
  
}