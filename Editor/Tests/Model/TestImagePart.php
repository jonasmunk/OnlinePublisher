<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestImagePart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(ImagePart::load(0));
    }

    function testCreate() {
        $obj = new ImagePart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(ImagePart::load($id));
		$obj->remove();
        $this->assertNull(ImagePart::load($id));
    }

	function testProperties() {
		$obj = new ImagePart();
		$obj->setImageId(10);
		$obj->save();
		
		$obj2 = ImagePart::load($obj->getId());
		$this->assertEqual($obj2->getImageId(),10);
		
		$obj2->remove();
	}
}
?>