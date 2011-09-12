<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestImageService extends UnitTestCase {
    
    function testCreateImageFromBase64() {
		$result = ImageService::createImageFromBase64(null,null,null);
		$this->assertFalse($result['success']);
		$this->assertNull($result['image']);
		
		$data = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAHUlEQVR42mNgoAewJg5QpoEkNlkaRp1EfScNMgAAFZU9ipnkh/gAAAAASUVORK5CYII=";
		$result = ImageService::createImageFromBase64($data,'myfile.png','My file');
		$this->assertTrue($result['success']);
		$image = $result['image'];
		
		$this->assertEqual($image->getSize(),86);
		$this->assertEqual($image->getHeight(),16);
		$this->assertEqual($image->getWidth(),16);
		$this->assertEqual($image->getMimetype(),'image/png');
		$this->assertEqual($image->getTitle(),'My file');
		
		$image->remove();
    }
}
?>