<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestHtmlDocument extends UnitTestCase {
    
    function testGetBodyContents() {
		$doc = new HtmlDocument();
		$this->assertTrue($doc->getBodyContents()=='');
		
		$doc = new HtmlDocument('<html><body></body></html>');
		$this->assertTrue($doc->getBodyContents()=='');
		
		$doc = new HtmlDocument('<html><body><p>fsdfjskjfsl</p></body></html>');
		$this->assertTrue($doc->getBodyContents()=='<p>fsdfjskjfsl</p>');
		
		$doc = new HtmlDocument('<html><body<p>fsdfjskjfsl</p></body></html>');
		$this->assertTrue($doc->getBodyContents()=='');
		
		$doc = new HtmlDocument('<html><body><p>fsdfjskjfsl</p></body</html>');
		$this->assertTrue($doc->getBodyContents()=='');
		
		$doc = new HtmlDocument('<html><body><p>¡“§£∞™¶[≤<>]”</p></body></html>');
		$this->assertTrue($doc->getBodyContents()=='<p>¡“§£∞™¶[≤<>]”</p>');
		
		$doc = new HtmlDocument('<html><body           ><p>fsdfjskjfsl</p></body    ></html>');
		$this->assertTrue($doc->getBodyContents()=='<p>fsdfjskjfsl</p>');
		
		//$doc = new HtmlDocument('<html><body ƒƒ†¥=ƒ†ƒƒƒƒƒ††¥ƒ†ƒ©«ƒ«≠≠""><p>fsdfjskjfsl</p></body></html>');
		//$this->assertTrue($doc->getBodyContents()=='<p>fsdfjskjfsl</p>');
		
		$doc = new HtmlDocument('<html><body abx=""><p>fsdfjskjfsl</p></body></html>');
		$this->assertTrue($doc->getBodyContents()=='<p>fsdfjskjfsl</p>');
    }
}
?>