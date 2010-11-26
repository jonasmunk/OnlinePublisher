<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Network/RemoteData.php');

class RemoteDataService {
	
	function _getUrlAsFile($url) {
		global $basePath;
		$path = RemoteDataService::getPathOfUrl($url);
		if (!file_exists($path)) {
			if ($file = fopen ($url, "rb")) {
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
	
	function getPathOfUrl($url) {
		global $basePath;
		return $basePath.'local/cache/urls/'.sha1($url);
	}
	
	function getRemoteData($query) {
		if (is_string($query)) {
			$url = $query;
		}
		$cached = Query::after('cachedurl')->withField('url',$url)->first();
		if (!$cached) {
			RemoteDataService::_getUrlAsFile($url);
			$cached = new Cachedurl();
			$cached->setUrl($url);
			$cached->setSynchronized(mktime());
			$cached->save();
		}
		$data = new RemoteData();
		
		$data->setAge(mktime()-$cached->getSynchronized());
		$data->setFile(RemoteDataService::getPathOfUrl($url));
		return $data;
	}
}