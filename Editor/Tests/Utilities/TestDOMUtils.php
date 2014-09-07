<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDOMUtils extends UnitTestCase {
	
	function testParse() {
		$doc = DOMUtils::parse("<people><person><name initials='jbm'><first>Jonas</first><last>Munk</last></name></person><![CDATA[hephey]]></people>");
		$this->assertEqual($doc->documentElement->tagName,'people');
		
		$name = DOMUtils::getFirstDescendant($doc,'name');
		$this->assertTrue($name->tagName,'name');
		
		$this->assertEqual($name->getAttribute('initials'),'jbm');
		
		$this->assertTrue($name->getAttribute('nothing')==='');
		$this->assertTrue($name->getAttribute('nothing')!==null);
	}
	
	function testParseFail() {
		$this->assertTrue(DOMUtils::parse(null)===null);
		$this->assertTrue(DOMUtils::parse('')===null);
		$this->assertTrue(DOMUtils::parse('abc')===null);
		$this->assertTrue(DOMUtils::parse('<abc>')===null);
	}
	
	function testParseSuccess() {
		$this->assertTrue(DOMUtils::parse('<abc/>')!==null);
	}
	
	function testVaild() {
		$this->assertTrue(DOMUtils::isValid('<abc/>'));
		$this->assertFalse(DOMUtils::isValid('<p></p><p></p>'));
		
		$this->assertTrue(DOMUtils::isValidFragment('<p></p><p></p>'));
	}
	
	function testGetText() {
		
		$doc = DOMUtils::parse("<people><![CDATA[hephey]]></people>");
		$this->assertEqual(DOMUtils::getText($doc),'hephey');
		
		$doc = DOMUtils::parse("<people><![CDATA[hep<hey]]> ziggy <h>hallo</h></people>");
		$this->assertEqual(DOMUtils::getText($doc),'hep<hey ziggy hallo');
	}
	
	function testGetInnerXML() {
		
		$doc = DOMUtils::parse("<people><h1>title</h1><p>text</p></people>");
		$this->assertEqual(DOMUtils::getInnerXML($doc->documentElement),'<h1>title</h1><p>text</p>');
		$this->assertEqual(DOMUtils::stripNamespaces('<h1 xmlns="http://uri.in2isoft.com/onlinepublisher/part/richtext/1.0/"><span xmlns="http://ns2.dk/">Please get me back!</span></h1>'),'<h1><span>Please get me back!</span></h1>');
	}
	
	function testChildren() {
		
		$doc = DOMUtils::parse("
			<people>
				<person>
					<name initials='jbm'><first>Jonas</first><last>Munk</last></name>
				</person>
				<dog>
					<name>Pluto</name>
				</dog>
				<person>
					<name>Michael Laudrup</name>
				</person>
				<![CDATA[hephey]]>
			</people>
		");
		$people = $doc->documentElement;
		$this->assertEqual(DOMUtils::getFirstChildElement($people)->tagName,'person');
		$this->assertEqual(DOMUtils::getFirstChildElement($people,'dog')->tagName,'dog');
		$this->assertEqual(count(DOMUtils::getChildElements($people,'person')),2);
		$this->assertEqual(count(DOMUtils::getChildElements($people)),3);
	}
  
    function _testParseAnything($input,$xml) {
        if (!is_array($input)) {
            $input = array($input);
        }
        
        foreach ($input as $value) {
    		$doc = DOMUtils::parseAnything($value);
    		$this->assertNotNull($doc->documentElement);
    		$this->assertEqual($xml,$doc->saveXML());
        }
    }

	function testParseAnything() {
        $this->_testParseAnything(
            array('<p>','<p></p>','<p/>'),
            "<?xml version=\"1.0\"?>\n<p/>\n"
        );
        
        $this->_testParseAnything(
            array('<p><</p>'),
            "<?xml version=\"1.0\"?>\n<p>&lt;&lt;/p&gt;</p>\n"
        );
        
		$doc = DOMUtils::parseAnything('');
		$this->assertNull($doc->documentElement);
		$this->assertEqual("<?xml version=\"1.0\"?>\n",$doc->saveXML());
    
		$doc = DOMUtils::parseAnything(' ');
		$this->assertNull($doc->documentElement);
		$this->assertEqual("<?xml version=\"1.0\"?>\n",$doc->saveXML());

		$doc = DOMUtils::parseAnything('abc');
		$this->assertNull($doc->documentElement);
		$this->assertEqual("<?xml version=\"1.0\"?>\n",$doc->saveXML());
	}
	
	function testParseHTML() {
		$doc = DOMUtils::parseHTML('<p>Hep hey</p>');
		$this->assertNotNull($doc);
		$this->assertNotNull($doc->documentElement);
		$this->assertEqual("<?xml version=\"1.0\" standalone=\"yes\"?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\n<html><body><p>Hep hey</p></body></html>\n",$doc->saveXML());
	}
	
	function testParseInvalidHTML() {
		$doc = DOMUtils::parseHTML('<p data="x">Hep hey</blockquote>');
		$this->assertNotNull($doc);
		$this->assertNotNull($doc->documentElement);
		$this->assertEqual("<?xml version=\"1.0\" standalone=\"yes\"?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\n<html><body><p data=\"x\">Hep hey</p></body></html>\n",$doc->saveXML());
	}
	
	function testParseHTMLFrgament() {
		$doc = DOMUtils::parseHTMLFragment('<p data="x">Hep hey</blockquote><p>fafaf</p>');
		$this->assertEqual('<p data="x">Hep hey</p><p>fafaf</p>',DOMUtils::getInnerXML($doc->documentElement));
	}
}