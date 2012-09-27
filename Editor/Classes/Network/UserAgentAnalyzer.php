<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class UserAgentAnalyzer {
	var $userAgent;
	var $applicationVersion;
	var $applicationName;
	var $technologyName;
	var $technologyVersion;
	var $isRobot;
	var $isSearchEngine;
	
	function UserAgentAnalyzer($userAgent=null) {
		if (!is_null($userAgent)) {
			$this->userAgent = $userAgent;
			$this->_analyze();
		}
	}
	
	function getShortID() {
		$str = '';
		if (is_string($this->applicationName)) {
			$str.=strtolower($this->applicationName).' '.strtolower($this->applicationName).$this->_toInt($this->applicationVersion);
		}
		if (is_string($this->technologyName)) {
			if ($str) {
				$str.=' ';
			}
			$str.=strtolower($this->technologyName).' '.strtolower($this->technologyName).$this->_toInt($this->technologyVersion);
		}
		return $str;
	}
	
	function _toInt($val) {
		if ($val) {
			return intval($val);
		}
		return '';
	}
	
	function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
		$this->_analyze();
	}
	
	function getApplicationVersion() {
		return $this->applicationVersion;
	}
	
	function getApplicationName() {
		return $this->applicationName;
	}
	
	function getTechnologyName() {
		return $this->technologyName;
	}
	
	function getTechnologyVersion() {
		return $this->technologyVersion;
	}
	
	function isRobot() {
		return $this->isRobot;
	}
	
	function isSearchEngine() {
		return $this->isSearchEngine;
	}
	
	function _reset() {
		$this->applicationVersion=null;
		$this->applicationName=null;
		$this->technologyName=null;
		$this->technologyVersion=null;
		$this->isRobot=null;
		$this->isSearchEngine=null;
	}
	
	function _analyze() {
		$this->_reset();
		//Mozilla/4.0 (compatible; MSIE 5.0; Windows NT; Girafabot; girafabot at girafa dot com; http://www.girafa.com)
		if (preg_match ("/Girafabot/i",$this->userAgent,$result)) {
			$this->technologyName = 'Girafabot';
			$this->technologyVersion = '';
			$this->applicationName = 'Girafabot';
			$this->applicationVersion = '';
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)
		elseif (preg_match ("/Mozilla\/[1-9].0 \(compatible; MSIE ([0-9\.bB]+); (Windows NT [5-9]\.[0-9]|Windows NT|Windows 98|Windows XP|Windows 95|Mac_PowerPC)(; [a-zA-Z-0-9\(\)\. =\{\},\/:\|#%]+)*\)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'InternetExplorer';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'InternetExplorer';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)
		elseif (preg_match ("/Mozilla\/[1-9].0 \(compatible; MSIE ([0-9\.bB]+); (Windows NT [5-9]\.[0-9]|Windows NT|Windows 98|Windows XP|Windows 95|Mac_PowerPC)(; [a-zA-Z-0-9\(\)\. =\{\},\/:\|#%]+)*\)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'InternetExplorer';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'InternetExplorer';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.0 (compatible; MSIE 6.0; AOL 8.0; Windows NT 5.1; FIPID-{0KUZjWJqnnqw{05je1XAf1sUU356964111; (R1 1.3); .NET CLR 1.1.4322)
		elseif (preg_match ("/Mozilla\/4.0 \(compatible; MSIE ([0-9\.]+); AOL ([0-9\.]+);/i",$this->userAgent,$result)) {
			$this->technologyName = 'InternetExplorer';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'AOL';
			$this->applicationVersion = $result[2];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da-DK; rv:1.7.8) Gecko/20050511 Firefox/1.0.4
		elseif (preg_match ("/Mozilla\/5.0 \([a-zA-Z0-9]+; [A-Z]; [a-zA-Z0-9 -\.]+; [a-zA-Z0-9 -]+; rv:([0-9\.a-zA-Z\+]+)\) Gecko\/[0-9]+ ([a-zA-Z]+)\/([0-9\.a-zA-Z\+]+)/",$this->userAgent,$result)) {
			$this->technologyName = 'Gecko';
			$this->technologyVersion = $result[1];
			$this->applicationName = $result[2];
			$this->applicationVersion = $result[3];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		// Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0.1
		elseif (preg_match ("/Mozilla\/5.0 \(([^;\)]+;)+ rv:([^\)]+)\) Gecko\/[0-9]+ ([a-zA-Z]+)\/([0-9\.a-zA-Z\+]+)/",$this->userAgent,$result)) {
			$this->technologyName = 'Gecko';
			$this->technologyVersion = $result[2];
			$this->applicationName = $result[3];
			$this->applicationVersion = $result[4];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b) Gecko/20050217
		elseif (preg_match ("/Mozilla\/5.0 \([a-zA-Z0-9]+; [A-Z]; [a-zA-Z0-9 -\.]+; [a-zA-Z0-9 -]+; rv:([0-9\.a-zA-Z\+]+)\) Gecko\/[0-9]+/",$this->userAgent,$result)) {
			$this->technologyName = 'Gecko';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Mozilla';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.0 (compatible; MSIE 5.0; Windows NT 4.0) Opera 6.04  [fr]
		elseif (preg_match ("/Opera[\/ ]([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'Opera';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Opera';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Safari/419.3
		//Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/125.5 (KHTML, like Gecko) Safari/125.9
		elseif (preg_match ("/Mozilla\/5.0 \(Macintosh; U; [a-zA-Z-]+ Mac OS X; [a-zA-Z-]+\) AppleWebKit\/([0-9\.+]+) \(KHTML, like Gecko\) ([a-zA-Z\!\.]+)\/([0-9\.]+)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'AppleWebKit';
			$this->technologyVersion = $result[1];
			$this->applicationName = $result[2];
			$this->applicationVersion = $result[3];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_1) AppleWebKit/537.10+ (KHTML, like Gecko) Version/6.0 Safari/536.25
		elseif (preg_match ("/Mozilla\/5.0 \([\w]+; [\\w ]+ [0-9_]+\) AppleWebKit\/([0-9\.+]+) \(KHTML, like Gecko\) Version\/([0-9\.]+) ([\w]+)\/([0-9\.]+)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'AppleWebKit';
			$this->technologyVersion = $result[1];
			$this->applicationName = $result[3];
			$this->applicationVersion = $result[4];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1
		elseif (preg_match ("/Mozilla\/5.0 \(([^;]+;)*[^\)]+\) AppleWebKit\/([0-9\.+]+) \(KHTML, like Gecko\) Chrome\/([0-9\.]+) ([\w]+)\/([0-9\.]+)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'AppleWebKit';
			$this->technologyVersion = $result[2];
			$this->applicationName = 'Chrome';
			$this->applicationVersion = $result[3];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		
		//Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/125.4 (KHTML, like Gecko, Safari) OmniWeb/v563.34
		elseif (preg_match ("/Mozilla\/5.0 \(Macintosh; U; [a-zA-Z-]+ Mac OS X; [a-zA-Z-]+\) AppleWebKit\/([0-9\.+]+) \(KHTML, like Gecko, Safari\) OmniWeb\/v([0-9\.]+)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'AppleWebKit';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'OmniWeb';
			$this->applicationVersion = $result[2];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412.7 (KHTML, like Gecko)
		elseif (preg_match ("/Mozilla\/5.0 \(Macintosh; U; PPC Mac OS X; [a-zA-Z-]+\) AppleWebKit\/([0-9\.+]+) \(KHTML, like Gecko\)\z/i",$this->userAgent,$result)) {
			$this->technologyName = 'AppleWebKit';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'AppleWebKit';
			$this->applicationVersion = '';
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.8 [en] (Windows NT 5.0; U)
		elseif (preg_match ("/Mozilla\/([0-9\.]+) \[[\w]+\]/i",$this->userAgent,$result)) {
			$this->technologyName = 'Netscape';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Netscape';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (compatible; Konqueror/3.4) KHTML/3.4.3 (like Gecko) (Kubuntu package 4:3.4.3-0ubuntu1)
		elseif (preg_match ("/Konqueror\/([0-9\.]+)\) KHTML\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'KHTML';
			$this->technologyVersion = $result[2];
			$this->applicationName = 'Konqueror';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (compatible; Konqueror/3.1)
		elseif (preg_match ("/Konqueror\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'KHTML';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Konqueror';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)
		elseif (preg_match ("/Googlebot\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'Googlebot';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Googlebot';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//msnbot/1.0 (+http://search.msn.com/msnbot.htm)
		elseif (preg_match ("/msnbot\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'msnbot';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'msnbot';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)
		elseif (preg_match ("/msnbot-media\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'msnbot-media';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'msnbot-medias';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//MJ12bot/v1.0.4 (http://majestic12.co.uk/bot.php?+)
		elseif (preg_match ("/MJ12bot\/v([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'MJ12bot';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'MJ12bot';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)
		elseif (preg_match ("/Yahoo! Slurp/i",$this->userAgent,$result)) {
			$this->technologyName = 'Yahoo! Slurp';
			$this->technologyVersion = '';
			$this->applicationName = 'Yahoo! Slurp';
			$this->applicationVersion = '';
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//W3C_Validator/1.432.2.10
		elseif (preg_match ("/W3C_Validator\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'W3C Validator';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'W3C Validator';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Jigsaw/2.2.3 W3C_CSS_Validator_JFouffa/2.0
		elseif (preg_match ("/Jigsaw\/[0-9\.]+ W3C_CSS_Validator_JFouffa\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'W3C CSS Validator';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'W3C CSS Validator';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.0 compatible ZyBorg/1.0 Dead Link Checker (wn.dlc@looksmart.net; http://www.WISEnutbot.com)
		elseif (preg_match ("/ZyBorg\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'ZyBorg';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'ZyBorg';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Kscrutor.dk abuse@scrutor.dk
		elseif (preg_match ("/scrutor.dk/i",$this->userAgent,$result)) {
			$this->technologyName = 'Scrutor Crawler';
			$this->technologyVersion = '';
			$this->applicationName = 'Scrutor Crawler';
			$this->applicationVersion = '';
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (compatible; iCab 3.0.2; Macintosh; U; PPC Mac OS X)
		elseif (preg_match ("/iCab ([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'iCab';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'iCab';
			$this->applicationVersion = $result[1];
			$this->isRobot = false;
			$this->isSearchEngine = false;
			return;
		}
		//Gigabot/2.0/gigablast.com/spider.html
		elseif (preg_match ("/Gigabot\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'Gigabot';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Gigabot';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//NutchCVS/0.8-dev (Nutch; http://lucene.apache.org/nutch/bot.html; nutch-agent@lucene.apache.org)
		elseif (preg_match ("/NutchCVS\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'NutchCVS';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'NutchCVS';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (compatible; heritrix/1.7.1-200601241521 +http://netarkivet.dk/website/info.html)
		elseif (preg_match ("/heritrix\/([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'Heritrix';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Heritrix';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = true;
			return;
		}
		//Mozilla/4.0 (compatible; Cerberian Drtrs Version-3.2-Build-0)
		elseif (preg_match ("/Cerberian Drtrs Version-([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'Cerberian Drtrs';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Cerberian Drtrs';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)
		elseif (preg_match ("/WinHttp\.WinHttpRequest\.([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'WinHttp';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'WinHttp';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)
		elseif (preg_match ("/WinHttp\.WinHttpRequest\.([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'WinHttp';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'WinHttp';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		//Mozilla/5.0 (Twiceler-0.9 http://www.cuill.com/twiceler/robot.html)
		elseif (preg_match ("/Mozilla\/5.0 \(Twiceler-([0-9\.]+)/i",$this->userAgent,$result)) {
			$this->technologyName = 'Twiceler';
			$this->technologyVersion = $result[1];
			$this->applicationName = 'Twiceler';
			$this->applicationVersion = $result[1];
			$this->isRobot = true;
			$this->isSearchEngine = false;
			return;
		}
		elseif (in_array($this->userAgent,array('HaliBot','PHP','Net Probe','dandirbot','kykapeky','ia_archiver','LinkWalker','psycheclone','panscient.com','Pingdom GIGRIB (http://www.pingdom.com)','EmeraldShield.com Web Spider (http://www.emeraldshield.com/webbot.aspx)','HenryTheMiragoRobot (http://www.miragorobot.com/scripts/dkinfo.asp)','khttp'))) {
			$this->technologyName = $this->userAgent;
			$this->applicationName = $this->userAgent;
			$this->isRobot = true;
		}
		//[name]/[version]
		elseif (preg_match ("/([a-zA-Z\-\. ]*)\/([0-9a-zA-Z\.\-]+)( \([.]*\))?/i",$this->userAgent,$result)) {
			$this->technologyName = $result[1];
			$this->technologyVersion = $result[2];
			$this->applicationName = $result[1];
			$this->applicationVersion = $result[2];
			$knownRobots = array('boitho.com-dc','silk','NetResearchServer','NPBot','curl','Microsoft-WebDAV-MiniRedir','findlinks','sproose','Jakarta Commons-HttpClient','Python-urllib','ccubee','Exabot','Jyxobot','Wget','khttp','HaliBot','PycURL','ConveraCrawler','Java','SiteSucker','ichiro','libwww-perl','HTTP','LinkWalker','psbot');
			$knownSearchEngines = array('findlinks','sproose','KompassBot','SurveyBot');
			if (in_array($result[1],$knownRobots)) {
				$this->isRobot = true;
			}
			if (in_array($result[1],$knownSearchEngines)) {
				$this->isRobot = true;
				$this->isSearchEngine = true;
			}
			return;
		}
		elseif (strlen($this->userAgent)==0) {
			$this->isRobot = true;
		}
	}
}
?>