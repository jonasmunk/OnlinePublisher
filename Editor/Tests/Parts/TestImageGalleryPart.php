<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

class TestImageGalleryPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(ImagegalleryPart::load(0));
    }

    function testCreate() {
        $obj = new ImagegalleryPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(ImagegalleryPart::load($id));
		$obj->remove();
        $this->assertNull(ImagegalleryPart::load($id));
    }

	function testProperties() {
		$obj = new ImagegalleryPart();
		$obj->setImageGroupId(10);
		$obj->save();
		
		$obj2 = ImageGalleryPart::load($obj->getId());
		$this->assertEqual($obj2->getImageGroupId(),10);
		
		$obj2->remove();
	}
}
?>