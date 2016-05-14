<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestFileSystemService extends UnitTestCase {
    
    function testSafeFileName() {
        //$this->assertEqual(FileSystemService::safeFileName('æøå.pdf'),'aeoeaa.pdf');
        $this->assertEqual(FileSystemService::safeFileName('filnavn.php'),'filnavn.php.txt');
    }
    
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

    function testOverwriteExtension() {
        $this->assertEqual(FileSystemService::overwriteExtension('filnavn.php','html'),'filnavn.html');
        $this->assertEqual(FileSystemService::overwriteExtension('filnavn.xml.php','html'),'filnavn.html');
        $this->assertEqual(FileSystemService::overwriteExtension('filnavn.xml.php',''),'filnavn');
        $this->assertEqual(FileSystemService::overwriteExtension('filnavn.xml.php',' '),'filnavn');
        $this->assertEqual(FileSystemService::overwriteExtension('filnavn.xml.php',null),'filnavn');
        $this->assertEqual(FileSystemService::overwriteExtension('/path/to/somewhere/filnavn.xml.php','jpg'),'/path/to/somewhere/filnavn.jpg');
        $this->assertEqual(FileSystemService::overwriteExtension('/path/to/some.where/filnavn.xml.php','jpg'),'/path/to/some.where/filnavn.jpg');
    }

    function testJoin() {
        $this->assertEqual(FileSystemService::join('a','b'),'a/b');
        $this->assertEqual(FileSystemService::join('a/','b'),'a/b');
        $this->assertEqual(FileSystemService::join('a','/b'),'a/b');
        $this->assertEqual(FileSystemService::join('a/','/b'),'a/b');
        $this->assertEqual(FileSystemService::join('a',''),'a');
        $this->assertEqual(FileSystemService::join('','b'),'b');
        $this->assertEqual(FileSystemService::join('a',null),'a');
        $this->assertEqual(FileSystemService::join(null,'b'),'b');
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