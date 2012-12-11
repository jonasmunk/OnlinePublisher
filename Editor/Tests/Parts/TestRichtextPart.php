<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestRichtextPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(RichtextPart::load(0));
    }

    function testCreate() {
        $obj = new RichtextPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(RichtextPart::load($id));
		$obj->remove();
        $this->assertNull(RichtextPart::load($id));
    }

	function testProperties() {
		$obj = new RichtextPart();
		$obj->setHtml('<h1>Test</h1>');
		$obj->save();
		
		$obj2 = RichtextPart::load($obj->getId());
		$this->assertEqual($obj2->getHtml(),'<h1>Test</h1>');
		
		$obj2->remove();
	}
	

	function testDisplay() {
		$obj = new RichtextPart();
		$obj->setHtml('<h1>Please get me back!</h1>');
		$ctrl = new RichtextPartController();
		
		$html = $ctrl->display($obj,new PartContext());
		$this->assertEqual(trim($html),'<div xmlns="http://www.w3.org/1999/xhtml" class="part_richtext common_font"><h1>Please get me back!</h1></div>');
	}

	function testImportValid() {
		$obj = new RichtextPart();
		$obj->setHtml('<h1>Please get me back!</h1>');
		$ctrl = new RichtextPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getHtml(),$obj->getHtml());
	}

	function testImportInvalid() {
		$obj = new RichtextPart();
		$obj->setHtml('Im in<alid<<>><');
		$ctrl = new RichtextPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getHtml(),$obj->getHtml());
	}
}
?>