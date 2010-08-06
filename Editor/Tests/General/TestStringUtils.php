<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class TestStringUtils extends UnitTestCase {
    
    function testEscaping() {
        $this->assertEqual("&#60;&#62;&#38;",StringUtils::escapeXML('<>&'));
		// TODO: is this correct?
		$this->assertEqual("&#195;&#166;",StringUtils::escapeXML('æ'));
    }

    function testUnicode() {
		// The encoding of this file should be UTF-8
		
		$this->assertEqual("æ",StringUtils::fromUnicode('Ã¦'));
		$this->assertEqual("Ã¦",StringUtils::toUnicode('æ'));
		$this->assertEqual("æ",StringUtils::toUnicode(StringUtils::fromUnicode('æ')));
	}

    function testBlank() {
        $this->assertTrue(StringUtils::isBlank(null));
        $this->assertFalse(StringUtils::isNotBlank(null));

		$this->assertTrue(StringUtils::isBlank(""));
		$this->assertFalse(StringUtils::isNotBlank(""));
		
		$this->assertTrue(StringUtils::isBlank(" "));
		$this->assertFalse(StringUtils::isNotBlank(" "));
		
		$this->assertTrue(StringUtils::isBlank("   "));
		$this->assertFalse(StringUtils::isNotBlank("   "));
		
		$this->assertTrue(StringUtils::isBlank("\n"));
		$this->assertFalse(StringUtils::isNotBlank("\n"));

		$this->assertFalse(StringUtils::isBlank("abc"));
		$this->assertTrue(StringUtils::isNotBlank("abc"));
    }
}
?>