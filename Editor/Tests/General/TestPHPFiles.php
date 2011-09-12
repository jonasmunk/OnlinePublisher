<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPHPFiles extends UnitTestCase {
	
	function testIt() {
		global $basePath,$baseUrl;
		
		$base = $basePath.'Editor/';
		
		$files = FileSystemService::find(array(
			'dir' => $base,
			'exclude' => array($base.'Libraries'),
			'extension' => 'php'
		));
		$this->assertTrue(is_dir($base));
		foreach ($files as $file) {
			$rel = substr($file,strlen($base));
			$url = 'http://localhost'.$baseUrl.'Editor/'.$rel;
			$excluded = array('Authentication.php','Recover.php','Include/Public.php','Touch/style.css.php','Services/Core/RecoverPassword.php','Services/Core/Authentication.php','Services/Core/ChangePassword.php');
			if (in_array($rel,$excluded)) {
				continue;
			}
			Log::debug($url);
			$this->_checkURL($url);
		}
	}
	
	function _checkURL($url) {
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		$error = curl_errno($ch);
		if ($error) {
			$this->assertTrue($error===0,'The result of url: '.$url.' had the error: '.$error);
			continue;
		}
		$info = curl_getinfo($ch);
		$httpCode = $info['http_code'];
		$this->assertTrue($httpCode===403 || $httpCode===302,'Http response code for '.$url.' is: '.$httpCode);
		$this->assertTrue(StringUtils::isBlank($result),'The result of url: '.$url.' should be blank, it is: '.$result);

		curl_close($ch);		
	}
}