<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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

	function testImport() {
		$obj = new ImagegalleryPart();
		$obj->setImageGroupId(10);
		$obj->setHeight(400);
		$obj->setFramed(true);
		$obj->setVariant('floating');
		$ctrl = new ImageGalleryPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getImageGroupId(),$obj->getImageGroupId());
		$this->assertIdentical($imported->getFramed(),$obj->getFramed());
		$this->assertIdentical($imported->getVariant(),$obj->getVariant());
		$this->assertIdentical($imported->getHeight(),$obj->getHeight());
	}
}
?>