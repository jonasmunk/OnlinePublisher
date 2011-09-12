<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPageblueprint extends AbstractObjectTest {
    
	function TestPageblueprint() {
		parent::AbstractObjectTest('pageblueprint');
	}
}
?>