<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWebloggroup extends AbstractObjectTest {
    
	function TestWebloggroup() {
		parent::AbstractObjectTest('webloggroup');
	}
	
	function testToString() {
	}
}
?>