<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestProject extends AbstractObjectTest {
    
	function TestProject() {
		parent::AbstractObjectTest('project');
	}
	
}
?>