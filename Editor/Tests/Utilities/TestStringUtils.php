<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

// THE ENCODING OF THIS FILE SHOULD BE UTF-8

class TestStringUtils extends UnitTestCase {
    
	function testTest() {
		$result = preg_replace("/&#(0|30);/e", '', 'hep &#30; &#0;hey');
		$this->assertEqual($result,'hep  hey');
	}

    function testStripInvalidXml() {
        $this->assertEqual('-1',StringUtils::stripInvalidXml('-1'));
        $this->assertEqual('-1',StringUtils::stripInvalidXml(-1));
        $this->assertEqual('0',StringUtils::stripInvalidXml(0));
        $this->assertEqual(' ',StringUtils::stripInvalidXml("\0"));
	}

    function testToString() {
        $this->assertEqual("0",StringUtils::toString(0));
	}
	
    function testEscaping() {
        $this->assertEqual("0",StringUtils::escapeXML(0));
        $this->assertEqual("0",StringUtils::escapeXML("0"));
        $this->assertEqual("&#60;&#62;&#38;",StringUtils::escapeXML('<>&'));
        $this->assertEqual("&quot;",StringUtils::escapeXML('"'));
        $this->assertEqual("&#38;",StringUtils::escapeXML('&'));
        $this->assertEqual("-",StringUtils::escapeXML('-'));
        $this->assertEqual("+",StringUtils::escapeXML('+'));
        $this->assertEqual("&#226;&#243;&#8220;",StringUtils::escapeXML('–'));
		// TODO: is this correct?
        $this->assertEqual("?",StringUtils::escapeXML(StringUtils::fromUnicode('–')));
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
		$this->assertEqual('lorem ipsum me@my @my.com hep hep',StringUtils::insertEmailLinks('lorem ipsum me@my @my.com hep hep'));
	}
	
	function testAppendWordToString() {
		$this->assertEqual('Jonas Munk',StringUtils::appendWordToString('Jonas','Munk',' '));
		$this->assertEqual('Jonas  Munk',StringUtils::appendWordToString('Jonas ','Munk',' '));
		$this->assertEqual('Munk',StringUtils::appendWordToString('','Munk',' '));
		$this->assertEqual('Munk',StringUtils::appendWordToString(null,'Munk',' '));
		$this->assertEqual('0 Munk',StringUtils::appendWordToString(0,'Munk',' '));
		$this->assertEqual('',StringUtils::appendWordToString(null,'',' '));
		$this->assertEqual('',StringUtils::appendWordToString(null,null,' '));
	}
	
	function testStartsWith() {
		$this->assertTrue(StringUtils::startsWith('Merge branch','Merge'));
	}
	
	function testRemoveTags() {
		$this->assertEqual('Jonas Munk',StringUtils::removeTags('Jonas <b>Munk</b>'));
		$this->assertEqual('Jonas Munk',StringUtils::removeTags('Jonas <b >Munk</b>'));
		$this->assertEqual('Jonas Munk',StringUtils::removeTags('Jonas <b style="._kfdjkjs">Munk</b>'));
		$this->assertEqual('Jonas < > Munk',StringUtils::removeTags('Jonas < > Munk'));
		$this->assertEqual('Jonas <> Munk',StringUtils::removeTags('Jonas <> Munk'));
	}
	
	function testBuildIndex() {
		$this->assertEqual('Jonas Munk',StringUtils::buildIndex(array('Jonas','Munk')));
		$this->assertEqual('Jonas Munk',StringUtils::buildIndex(array(' ','Jonas ',null,'  Munk',null,'')));
		$this->assertEqual('',StringUtils::buildIndex(null));
	}
	
	function testExtract() {
		$str = 'hep hey <table><tr><td>hdsjfhafkhk</td></tr></table> hey <table></table><table border="1">--</table>';
		$extracted = StringUtils::extract($str,'<table','table>');
		$this->assertEqual($extracted[0],'<table><tr><td>hdsjfhafkhk</td></tr></table>');
		$this->assertEqual($extracted[1],'<table></table>');
		$this->assertEqual($extracted[2],'<table border="1">--</table>');
	}
}
?>