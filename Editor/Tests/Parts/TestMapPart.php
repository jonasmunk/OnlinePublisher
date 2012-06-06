<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMapPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(MapPart::load(0));
    }

    function testCreate() {
        $obj = new MapPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(MapPart::load($id));
		$obj->remove();
        $this->assertNull(MapPart::load($id));
    }

	function testProperties() {
		$obj = new MapPart();
		$obj->setMaptype('terrain');
		$obj->save();
		
		$loaded = MapPart::load($obj->getId());
		$this->assertEqual($loaded->getMaptype(),'terrain');
		
		$loaded->remove();
	}

	function testImport() {
		$obj = new MapPart();
		$obj->setMaptype('Please get me back!');
		$ctrl = new MapPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getMaptype(),$obj->getMaptype());
	}
}
?>