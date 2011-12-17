<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once $basePath.'Editor/Libraries/pclzip/pclzip.lib.php';

class ZipService {
	
	function uploadIsZipFile($name='file') {
		$mimes = array("application/x-zip-compressed","application/zip");
		return in_array($_FILES[$name]["type"],$mimes);
	}
	
	function getArchive($path) {
		$zip = new PclZip($path);
		return new ZipArchive($zip);
	}

	function getUploadedZip($name='file') {
		return ZipService::getArchive($_FILES[$name]["tmp_name"]);
	}
}