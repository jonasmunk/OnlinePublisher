<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
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

	function testImport() {
		$obj = new ImagePart();
		$latest = ImageService::getLatestImageId();
		if ($latest==null) {
			Log::debug('This test can only run with at least one image present');
			return;
		}
		$obj->setImageId($latest);
		$obj->setGreyscale(true);
		$obj->setScaleMethod('max');
		$obj->setScaleWidth(100);
		$obj->setScaleHeight(200);
		$obj->setAlign('center');
		$obj->setText('This is the text');
		$ctrl = new ImagePartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getImageId(),$obj->getImageId());
		$this->assertIdentical($imported->getGreyscale(),$obj->getGreyscale());
		$this->assertIdentical($imported->getScaleMethod(),$obj->getScaleMethod());
		$this->assertIdentical($imported->getScaleWidth(),$obj->getScaleWidth());
		$this->assertIdentical($imported->getScaleHeight(),$obj->getScaleHeight());
		$this->assertIdentical($imported->getAlign(),$obj->getAlign());
		$this->assertIdentical($imported->getText(),$obj->getText());
	}

	function testImportPercent() {
		$obj = new ImagePart();
		$latest = ImageService::getLatestImageId();
		if ($latest==null) {
			Log::debug('This test can only run with at least one image present');
			return;
		}
		$obj->setImageId($latest);
		$obj->setGreyscale(true);
		$obj->setScaleMethod('percent');
		$obj->setScalePercent(50);
		$obj->setAlign('center');
		$obj->setText('This is the text');
		$ctrl = new ImagePartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		$this->assertNotNull($imported);
		return;
		$this->assertIdentical($imported->getImageId(),$obj->getImageId());
		$this->assertIdentical($imported->getGreyscale(),$obj->getGreyscale());
		$this->assertIdentical($imported->getScaleMethod(),$obj->getScaleMethod());
		$this->assertIdentical($imported->getScalePercent(),$obj->getScalePercent());
		$this->assertIdentical($imported->getAlign(),$obj->getAlign());
		$this->assertIdentical($imported->getText(),$obj->getText());
	}
}
?>