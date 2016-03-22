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
    if (!$result['success']) {
      Log::debug($result);
    }
    $image = $result['image'];
    $this->assertEqual($image->getSize(),86);
    $this->assertEqual($image->getHeight(),16);
    $this->assertEqual($image->getWidth(),16);
    $this->assertEqual($image->getMimetype(),'image/png');
    $this->assertEqual($image->getTitle(),'My file');
    
    $image->remove();
  }


  function testCreateImageFromFile() {
    global $basePath;
    $path = $basePath.'Editor/Tests/Resources/actually_a_jpeg.png';
    $result = ImageService::createImageFromFile($path);

    $this->assertNotNull($result);
    
    $image = $result->getObject();
    
    $this->assertEqual($image->getWidth(),548);
    $this->assertEqual($image->getHeight(),448);
    $this->assertEqual($image->getMimetype(),'image/jpeg');
    $this->assertEqual($image->getSize(),109338);

    $imagePath = $basePath.'images/'.$image->getFilename();
    $this->assertTrue(file_exists($imagePath));

    $ext = FileSystemService::getFileExtension($imagePath);
    $this->assertEqual($ext,'jpg');

    $image->remove();
  }

  function testCreateImageFromUnknownFile() {
    global $basePath;
    $path = $basePath.'Editor/Tests/Resources/jpeg_with_no_extension';
    $result = ImageService::createImageFromFile($path);

    $this->assertNotNull($result);
    
    $image = $result->getObject();
    
    $this->assertEqual($image->getWidth(),548);
    $this->assertEqual($image->getHeight(),448);
    $this->assertEqual($image->getMimetype(),'image/jpeg');
    $this->assertEqual($image->getSize(),109338);

    $imagePath = $basePath.'images/'.$image->getFilename();
    $this->assertTrue(file_exists($imagePath));

    $ext = FileSystemService::getFileExtension($imagePath);
    $this->assertEqual($ext,'jpg');

    $image->remove();
  }


  function testCustomFileName() {
    global $basePath;
    $unique = md5(uniqid(rand(), true));
    $path = $basePath.'Editor/Tests/Resources/jonasmunk.jpg';
    $result = ImageService::createImageFromFile($path,$unique.'.png');

    $this->assertNotNull($result);
    $this->assertTrue($result->getSuccess());
    
    $image = $result->getObject();
    $this->assertNotNull($image);
    if ($image) {
      $this->assertEqual($image->getWidth(),548);
      $this->assertEqual($image->getHeight(),448);
      $this->assertEqual($image->getMimetype(),'image/jpeg');
      $this->assertEqual($image->getSize(),109338);

      $this->assertEqual($image->getFilename(),$unique.'.jpg');

      $image->remove();     
    }
  }
}
?>