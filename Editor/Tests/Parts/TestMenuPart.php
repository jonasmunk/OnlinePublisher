<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMenuPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(MenuPart::load(0));
    }

    function testCreate() {
        $obj = new MenuPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(MenuPart::load($id));
		$obj->remove();
        $this->assertNull(MenuPart::load($id));
    }

	function testProperties() {
		$obj = new MenuPart();
		$obj->setHierarchyId(10);
		$obj->setVariant('plain');
		$obj->setDepth(5);
		$obj->save();
		
		$loaded = MenuPart::load($obj->getId());
        $this->assertNotNull($loaded);
		$this->assertEqual($loaded->getHierarchyId(),10);
		$this->assertEqual($loaded->getVariant(),'plain');
		$this->assertEqual($loaded->getDepth(),5);
		
		$loaded->remove();
	}

	function testImport() {
		$obj = new MenuPart();
		$obj->setHierarchyId(1234);
		$obj->setVariant('Get me back!');
		$obj->setDepth(3);
		$ctrl = new MenuPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getHierarchyId(),$obj->getHierarchyId());
		$this->assertIdentical($imported->getVariant(),$obj->getVariant());
		$this->assertIdentical($imported->getDepth(),$obj->getDepth());
	}
        
}
?>