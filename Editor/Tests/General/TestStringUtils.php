<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

// THE ENCODING OF THIS FILE SHOULD BE UTF-8

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

    function testSummarize() {
		$this->assertEqual("onc<highlight>e</highlight> upon a tim<highlight>e</highlight>",StringUtils::summarizeAndHighlight(array("e"),"once upon a time"));
	}
	
	function testInsertEmail() {
		$this->assertEqual('lorem ipsum <a href="mailto:me@my.com">me@my.com</a> hep hep',StringUtils::insertEmailLinks('lorem ipsum me@my.com hep hep'));
		$this->assertEqual('lorem ipsum me@my @my.com hep hepGG',StringUtils::insertEmailLinks('lorem ipsum me@my @my.com hep hep'));
	}
}
?>