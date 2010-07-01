<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Settings.php');

class BumbleBee {

	var $dir;
	
	function BumbleBee() {
		
	}
	
	function getBaseAddress() {
		$address = Settings::getSetting('system','environment','worker-server-address',0);
		return $address;
	}
	
	function renderWebPage($url,$path,$format) {
		$address = $this->getBaseAddress()."/service/webrenderer/?url=".urlencode($url).'&format='.$format;
		if ($file = fopen ($address, "rb")) {
		    $temp = fopen($path, "wb");
			while (!feof($file)) {
				fwrite($temp,fread($file, 8192));
			}
		    fclose($temp);
			fclose($file);
		} else {
			error_log('Could not open file');
		}
	}
	
	//////////////////////// Static /////////////////////
	
	function isConfigured() {
		$configured = false;
		$configured = strlen(Settings::getSetting('system','environment','worker-server-address',0))>0;
		//return false;
		return $configured;
	}
}
?>