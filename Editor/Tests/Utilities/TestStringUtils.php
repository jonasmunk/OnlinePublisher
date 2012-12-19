<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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

    function testSplitIntegers() {
        $this->assertIdentical(array(0),StringUtils::splitIntegers("0"));
        $this->assertIdentical(array(),StringUtils::splitIntegers(""));
        $this->assertIdentical(array(),StringUtils::splitIntegers(null));
        $this->assertIdentical(array(0),StringUtils::splitIntegers(0));
        $this->assertIdentical(array(0,-23,56),StringUtils::splitIntegers("0,-23,56"));
        $this->assertIdentical(array(0,-23,56),StringUtils::splitIntegers("0 ,  -23 , 56   ,x"));
        $this->assertIdentical(array(-23,56),StringUtils::splitIntegers("0xx ,  -23 , 56   ,x"));
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
		
		$this->assertEqual("-",StringUtils::fromUnicode('–'),"Long dash is replaced with short dash");
		$this->assertEqual("\"",StringUtils::fromUnicode('”'),"Curcly quotes are replaced with normal quotes");
		$this->assertEqual("\"",StringUtils::fromUnicode('“'),"Curcly quotes are replaced with normal quotes");
		// TODO: is this correct?
		$this->assertEqual("&#230;",StringUtils::escapeXML(StringUtils::fromUnicode('æ')));
    }

    function testUnicode() {
		// The encoding of this file should be UTF-8
		
		$this->assertEqual("æ",StringUtils::fromUnicode('Ã¦'));
		$this->assertEqual("Ã¦",StringUtils::toUnicode('æ'));
		$this->assertEqual("æ",StringUtils::toUnicode(StringUtils::fromUnicode('æ')));
		
		$obj = array('first'=>'æ','sub'=>array('one'=>'æ'));
		$obj = StringUtils::toUnicode($obj);
		$this->assertEqual("Ã¦",$obj['first']);
		$this->assertEqual("Ã¦",$obj['sub']['one']);
		
		$group = new Imagegroup();
		$group->setTitle('æ');
		StringUtils::toUnicode($group);
		$this->assertEqual("Ã¦",$group->getTitle());
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
	
	function testEndsWith() {
		$this->assertTrue(StringUtils::endsWith('Merge branch','branch'));
		$this->assertTrue(StringUtils::endsWith('http://www.humanize.dk/','/'));
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
	
	function testConcatUrl() {
		$this->assertEqual(StringUtils::concatUrl(null,null),'');
		$this->assertEqual(StringUtils::concatUrl('http://www.humanize.dk/',null),'http://www.humanize.dk/');
		$this->assertEqual(StringUtils::concatUrl('http://www.humanize.dk','/path/to/somewhere.html'),'http://www.humanize.dk/path/to/somewhere.html');
		$this->assertEqual(StringUtils::concatUrl('http://www.humanize.dk/','path/to/somewhere.html'),'http://www.humanize.dk/path/to/somewhere.html');
		$this->assertEqual(StringUtils::concatUrl('http://www.humanize.dk/','/path/to/somewhere.html'),'http://www.humanize.dk/path/to/somewhere.html');
	}
}
?>