<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

class TestFileSystemService extends UnitTestCase {
    
    function testGetFileExtension() {
        $this->assertTrue(FileSystemService::getFileExtension('filnavn.php')=='php');
        $this->assertTrue(FileSystemService::getFileExtension('filnavn.php.xml')=='xml');
        $this->assertTrue(FileSystemService::getFileExtension('.xml')=='xml');
        $this->assertTrue(FileSystemService::getFileExtension('xml')=='');
        $this->assertTrue(FileSystemService::getFileExtension('')=='');
        $this->assertTrue(FileSystemService::getFileExtension('filnavn')=='');
    }
    
    function testGetFileTitle() {
        $this->assertTrue(FileSystemService::filenameToTitle('filnavn.php')=='Filnavn');
        $this->assertTrue(FileSystemService::filenameToTitle('filnavn.php.xml')=='Filnavn');
        $this->assertTrue(FileSystemService::filenameToTitle('filnavn')=='Filnavn');
        $this->assertTrue(FileSystemService::filenameToTitle('my_photo')=='My photo');
        $this->assertTrue(FileSystemService::filenameToTitle('my_photo.jpg')=='My photo');
        $this->assertTrue(FileSystemService::filenameToTitle('')=='');
    }
    
    function testGetExtension() {
        $this->assertEqual(FileService::mimeTypeToExtension('image/jpeg'),'jpg');
        $this->assertEqual(FileService::mimeTypeToExtension('text/html'),'html');
        $this->assertEqual(FileService::mimeTypeToExtension('text/html'),'html');

        $this->assertEqual(FileService::extensionToMimeType('html'),'text/html');
        $this->assertEqual(FileService::extensionToMimeType('jpg'),'image/jpeg');
        $this->assertEqual(FileService::extensionToMimeType('jpeg'),'image/jpeg');
        $this->assertEqual(FileService::extensionToMimeType('doc'),'application/msword');
        $this->assertEqual(FileService::extensionToMimeType('txt'),'text/plain');
    }
}
?>