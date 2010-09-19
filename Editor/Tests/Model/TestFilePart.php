<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFilePart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(FilePart::load(0));
    }

    function testCreate() {
        $obj = new FilePart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(FilePart::load($id));
		$obj->remove();
        $this->assertNull(FilePart::load($id));
    }

	function testProperties() {
		$obj = new FilePart();
		$obj->setFileId(10);
		$obj->save();
		
		$obj2 = FilePart::load($obj->getId());
		$this->assertEqual($obj2->getFileId(),10);
		
		$obj2->remove();
	}
}
?>