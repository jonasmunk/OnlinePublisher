<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ConfigurationService {

	static function isDebug() {
		global $CONFIG;
		return (isset($CONFIG) && isset($CONFIG['debug']) && $CONFIG['debug']==true);
	}

	private static function _getConfig($name,$default = null) {
		global $CONFIG;
		if (isset($CONFIG) && isset($CONFIG[$name])) {
			return $CONFIG[$name];
		}
		return $default;
	}

	static function isUnicode() {
		global $CONFIG;
		return (isset($CONFIG) && (!isset($CONFIG['unicode']) || $CONFIG['unicode']==true));
	}

	static function isGatherStatistics() {
		return ConfigurationService::_getConfig('statistics',true);
	}

	static function getDeploymentTime() {
		$time = ConfigurationService::_getConfig('deployed',0);
    if ($time==0) {
      $time = SystemInfo::getDate();
    }
    return $time;
	}

	static function isCachePages() {
		return ConfigurationService::_getConfig('cachepages',!true);
	}

	static function getShellPath() {
		return ConfigurationService::_getConfig('shellpath','');
	}

	static function isUrlRewrite() {
		return isset($GLOBALS['CONFIG']) && @$GLOBALS['CONFIG']['urlrewrite'];
	}

	static function isOptimizeHTML() {
		global $CONFIG;
    if (isset($CONFIG) && isset($CONFIG['optimize'])) {
      return $CONFIG['optimize'];
    }
		return true;
	}

	static function getBaseUrl() {
		global $CONFIG,$baseUrl;
		if (isset($CONFIG) && isset($CONFIG['baseUrl'])) {
			return $CONFIG['baseUrl'];
		}
		return $baseUrl;
	}

	static function getDatabase() {
		global $CONFIG,$database_host, $database_user,$database_password,$database;
		if (isset($CONFIG) && isset($CONFIG['database'])) {
			return $CONFIG['database'];
		}
		return array(
			'host' => $database_host,
			'user' => $database_user,
			'password' => $database_password,
			'database' => $database
		);
	}

	static function getSuperUsername() {
		global $CONFIG,$superUser;
		if (isset($CONFIG) && isset($CONFIG['super']) && isset($CONFIG['super']['user'])) {
			return $CONFIG['super']['user'];
		}
		return $superUser;
	}

	static function getSuperPassword() {
		global $CONFIG,$superPassword;
		if (isset($CONFIG) && isset($CONFIG['super']) && isset($CONFIG['super']['password'])) {
			return $CONFIG['super']['password'];
		}
		return $superPassword;
	}

	static function getCompleteBaseUrl() {
		$url = ConfigurationService::getBaseUrl();
		if (!Strings::startsWith($url,'http')) {
			$url = 'http://localhost'.$url;
		}
		return $url;
	}

  static function getImagePath($filename) {
		global $CONFIG,$basePath;
    if (isset($CONFIG['dataDir'])) {
      return $basePath.$CONFIG['dataDir'].'images/'.$filename;
    }
  	return $basePath.'images/'.$filename;
  }

  static function getDataPath($path) {
		global $CONFIG,$basePath;
    if (isset($CONFIG['dataDir'])) {
      return FileSystemService::join($basePath . $CONFIG['dataDir'], $path);
    }
    return FileSystemService::join($basePath, $path);
  }

  static function getDataUrl() {
		global $CONFIG,$basePath;
    $base = ConfigurationService::getBaseUrl();
    if (isset($CONFIG['dataDir'])) {
      return $base . $CONFIG['dataDir'];
    }
    return $base;
  }
}