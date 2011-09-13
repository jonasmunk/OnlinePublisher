<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWeblogentry extends AbstractObjectTest {
    
	function TestWeblogentry() {
		parent::AbstractObjectTest('weblogentry');
	}
	
	function testToString() {
	}
}
?>