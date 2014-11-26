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
	
	function testBuildAbstractUI() {
		$path = FileService::getPath('Editor/Tests/Resources/abstract_ui.xml');
		$this->assertTrue(file_exists($path));
        
        $xml = file_get_contents($path);
        
        $result = UI::buildAbstractUI($xml);
        
        // TODO Do some actual testing :-)
	}

	function testLocalize() {
		$str = '<tag title="{ View ; da: Vis: }" label="{ en: Edit ; da: Rediger mig }" />';
		$result = UI::localize($str,'en');
		$this->assertEqual($result,'<tag title="View" label="Edit" />');
		
		$result = UI::localize($str,'da');
		$this->assertEqual($result,'<tag title="Vis:" label="Rediger mig" />');

		$str = '<tag>{ View ; da: Vis: }</tag>';
		$result = UI::localize($str,'en');
		$this->assertEqual($result,'<tag>View</tag>');

	}
}