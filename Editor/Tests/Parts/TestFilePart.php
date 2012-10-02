<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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

	function testImport() {
		$obj = new FilePart();
		$latest = FileService::getLatestFileId();
		if ($latest==null) {
			Log::debug('This test can only run with at least one file present');
			return;
		}
		$obj->setFileId($latest);
		$obj->setText('Get me back!');
		$ctrl = new FilePartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getFileId(),$obj->getFileId());
		$this->assertIdentical($imported->getText(),$obj->getText());
	}
}
?>