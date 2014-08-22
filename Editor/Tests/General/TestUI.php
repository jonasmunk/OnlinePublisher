<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestUI extends UnitTestCase {
	
	function testIt() {
		$path = FileService::getPath('Editor/Tests/Resources/abstract_ui.xml');
		$this->assertTrue(file_exists($path));
        
        $xml = file_get_contents($path);
        
        $result = UI::buildAbstractUI($xml);
        
        //print $result;
	}
}