<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestImageGallery extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(Imagegallery::load(0));
    }

    function testCreate() {
        $news = new Imagegallery();
		$this->assertFalse($news->isPersistent());
		$news->save();
		$this->assertTrue($news->isPersistent());
		$id = $news->getId();
        $this->assertNotNull(Imagegallery::load($id));
		$news->remove();
        $this->assertNull(Imagegallery::load($id));
    }

	function testProperties() {
		$obj = new Imagegallery();
		$obj->setImageGroupId(10);
		$obj->save();
		
		$obj2 = ImageGallery::load($obj->getId());
		$this->assertEqual($obj2->getImageGroupId(),10);
		
		$obj2->remove();
	}
}
?>