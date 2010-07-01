<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

require_once('../../Config/Setup.php');
require_once('../Include/Security.php');

require_once('../Libraries/simpletest/unit_tester.php');
require_once('../Libraries/simpletest/reporter.php');

require_once('../Classes/FileSystemUtil.php');

class TestFiles extends UnitTestCase {
    
    function testGetFileExtension() {
        $this->assertTrue(FileSystemUtil::getFileExtension('filnavn.php')=='php');
        $this->assertTrue(FileSystemUtil::getFileExtension('filnavn.php.xml')=='xml');
        $this->assertTrue(FileSystemUtil::getFileExtension('.xml')=='xml');
        $this->assertTrue(FileSystemUtil::getFileExtension('xml')=='');
        $this->assertTrue(FileSystemUtil::getFileExtension('')=='');
    }
    
    function testGetFileTitle() {
        $this->assertTrue(FileSystemUtil::filenameToTitle('filnavn.php')=='Filnavn');
        $this->assertTrue(FileSystemUtil::filenameToTitle('filnavn.php.xml')=='Filnavn');
        $this->assertTrue(FileSystemUtil::filenameToTitle('filnavn')=='Filnavn');
        $this->assertTrue(FileSystemUtil::filenameToTitle('')=='');
    }
    
    function testGetExtension() {
        $this->assertTrue(FileSystemUtil::mimeTypeToExtension('text/html')=='html');
        $this->assertTrue(FileSystemUtil::extensionToMimeType('html')=='text/html');
    }
}
?>