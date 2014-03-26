<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMoviePart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(MoviePart::load(0));
    }

    function testCreate() {
        $obj = new MoviePart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(MoviePart::load($id));
		$obj->remove();
        $this->assertNull(MoviePart::load($id));
    }

	function testProperties() {
		$obj = new MoviePart();
		$obj->setFileId(10);
		$obj->setImageId(4);
		$obj->save();
		
		$obj2 = MoviePart::load($obj->getId());
		$this->assertEqual($obj2->getFileId(),10);
		$this->assertEqual($obj2->getImageId(),4);
		
		$obj2->remove();
	}

	function testImport() {
		$obj = new MoviePart();
		$latest = FileService::getLatestFileId();
		if ($latest==null) {
			Log::debug('This test can only run with at least one file present');
			return;
		}
		$obj->setFileId($latest);
		$obj->setText('Get me back!');
		$obj->setCode('<iframe src="http://get.me/back"></iframe>');

		$ctrl = new MoviePartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getFileId(),$obj->getFileId());
		$this->assertIdentical($imported->getText(),$obj->getText());
		$this->assertIdentical($imported->getCode(),$obj->getCode());
	}
}
?>