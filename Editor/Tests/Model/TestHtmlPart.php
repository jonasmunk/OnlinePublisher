<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestHtmlPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(HtmlPart::load(0));
    }

    function testCreate() {
        $obj = new HtmlPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(HtmlPart::load($id));
		$obj->remove();
        $this->assertNull(HtmlPart::load($id));
    }

	function testProperties() {
		$obj = new HtmlPart();
		$obj->setHtml('<h1>Test</h1>');
		$obj->save();
		
		$obj2 = HtmlPart::load($obj->getId());
		$this->assertEqual($obj2->getHtml(),'<h1>Test</h1>');
		
		$obj2->remove();
	}

	function testImport() {
		$obj = new HtmlPart();
		$obj->setHtml('<h1>Please get me back!</h1>');
		$ctrl = new HtmlPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getHtml(),$obj->getHtml());
	}
}
?>