<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestListPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(ListPart::load(0));
    }

    function testCreate() {
        $obj = new ListPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(ListPart::load($id));
		$obj->remove();
        $this->assertNull(ListPart::load($id));
    }

	function testObjects() {
		$obj = new ListPart();
		$obj->setObjectIds(array(10,12,345));
		$obj->save();
		
		$obj2 = ListPart::load($obj->getId());
		$ids = $obj2->getObjectIds();
		
		$this->assertTrue(in_array(10,$ids));
		$this->assertTrue(in_array(12,$ids));
		$this->assertTrue(in_array(345,$ids));
		
		$obj2->remove();
	}
}
?>