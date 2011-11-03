<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestZipArchive extends UnitTestCase {
		
	function testParseFile() {
		global $basePath;
		$path = $basePath.'Editor/Tests/Resources/images.zip';
		$archive = ZipService::getArchive($path);
		
		$this->assertNotNull($archive);
		
		$files = $archive->getFiles();
		$this->assertEqual(count($files),3);
		foreach ($files as $file) {
			$extracted = $file->extract();
			Log::debug($extracted);
			$this->assertTrue(file_exists($extracted));
			unlink($extracted);
			$this->assertFalse(file_exists($extracted));
		}
	}

	function testParsePages() {
		global $basePath;
		$path = $basePath.'Editor/Tests/Resources/pages.pages';
		$archive = ZipService::getArchive($path);
		
		$this->assertNotNull($archive);
		
		$folder = $archive->extractToTemporaryFolder();
		$this->assertTrue(file_exists($folder->getPath()));
		
		$files = $folder->getFiles();
		Log::debug($files);
		$folder->remove();
		$this->assertFalse(file_exists($folder->getPath()));
	}

}