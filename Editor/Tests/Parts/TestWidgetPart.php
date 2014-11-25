<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWidgetPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(WidgetPart::load(0));
    }

    function testCreate() {
        $obj = new WidgetPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(WidgetPart::load($id));
		$obj->remove();
        $this->assertNull(WidgetPart::load($id));
    }

	function testProperties() {
		$obj = new WidgetPart();
		$obj->setKey('akey');
		$obj->setData('<data/>');
		$obj->save();
		
		$loaded = WidgetPart::load($obj->getId());
		$this->assertEqual('akey',$loaded->getKey());
		$this->assertEqual('<data/>',$loaded->getData());
		
		$loaded->remove();
	}

	function testImport() {
		$obj = new WidgetPart();
		$obj->setKey('mykey');
		$obj->setData('<data/>');
		$ctrl = new WidgetPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getKey(),$obj->getKey());
		$this->assertIdentical($imported->getData(),$obj->getData());
	}
}
?>