<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

class TestXmlService extends UnitTestCase {
    
    function testValidate() {
        $this->assertTrue(XmlService::validateSnippet('<x>ashashk</x>'));
		$this->assertTrue(XmlService::validateSnippet('<?xml version="1.0"?><x>ashashk</x>'));
		
		$this->assertFalse(XmlService::validateSnippet('<xml version="1.0"?><x>ashashk</x>'));
		$this->assertFalse(XmlService::validateSnippet('ashashk'));
    }
}
?>