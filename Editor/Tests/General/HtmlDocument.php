<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

require_once('../../Config/Setup.php');
require_once('../Include/Security.php');

require_once('../Libraries/simpletest/unit_tester.php');
require_once('../Libraries/simpletest/reporter.php');
require_once('../Classes/HtmlDocument.php');

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