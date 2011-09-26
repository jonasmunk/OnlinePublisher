<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Core/Log.php');
require_once($basePath.'Editor/Classes/Network/RemoteData.php');

class RemoteDataService {
	
	function _getUrlAsFile($url) {
		global $basePath;
		$path = RemoteDataService::getPathOfUrl($url);
		if (!file_exists($path)) {
			$success = RemoteDataService::writeUrlToFile($url,$path);
			if ($file = fopen($url, "rb")) {
			    if ($temp = fopen($path, "wb")) {
					while (!feof($file)) {
						fwrite($temp,fread($file, 8192));
					}
				    fclose($temp);
				}
				fclose($file);
			}
		}
		return $path;
	}
	
	function writeUrlToFile($url,$path) {
		$success = false;
		if (!function_exists('curl_init')) {
			if ($file = @fopen($url, "rb")) {
			    if ($temp = @fopen($path, "wb")) {
					while (!feof($file)) {
						fwrite($temp,fread($file, 8192));
					}
				    fclose($temp);
					$success = true;
				}
				fclose($file);
			}
		} else {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HEADER, 0);
			if ($file = fopen($path, "wb")) {
		    	curl_setopt($ch, CURLOPT_FILE, $file);
            	$success = curl_exec($ch);
				fclose($file);
				if (!$success) {
					unlink($path);
					Log::debug('Unable to load url: '.$url);
				}
			} else {
				Log::debug('Unable to open file path: '.$path);
			}
			curl_close($ch);			
		}
		return $success;
	}
	
	function getPathOfUrl($url) {
		global $basePath;
		return $basePath.'local/cache/urls/'.sha1($url);
	}
	
	/**
	 * @param $maxAge The number of seconds 
	 */
	function getRemoteData($url,$maxAge=0) {
		$now = mktime();
		$cached = Query::after('cachedurl')->withProperty('url',$url)->first();
		$path = RemoteDataService::getPathOfUrl($url);
		$success = false;
		if (!$cached) {
			$cached = new Cachedurl();
			$cached->setTitle($url);
			$cached->setUrl($url);
			$cached->setSynchronized(0);
		} else {
			$success = false;
		}
		$age = $now-$cached->getSynchronized();
		if ($age>$maxAge) {
			$success = RemoteDataService::writeUrlToFile($url,$path);
			$cached->setTitle($url);
			$cached->setSynchronized(mktime());
			$cached->save();
			$cached->publish();
		}
		$data = new RemoteData();
		$data->setAge($age);
		$data->setFile(RemoteDataService::getPathOfUrl($url));
		$data->setSuccess($success);
		$data->setHasData(file_exists($path) && filesize($path)>0);
		return $data;
	}
}