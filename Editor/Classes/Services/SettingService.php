<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class SettingService {

	/**
	 * @static
	 * Sets a setting
	 * @param string $domain The domain of the setting (system,tool,template...)
	 * @param string $subdomain The domain of the setting (pages,document,mail...)
	 * @param string $key The name of the setting
	 * @param string $value The value of the setting
	 * @param int $user Optinal User ID, defaults to all users (0)
	 * @return boolean True on success, False otherwise
	 */
	function setSetting($domain,$subdomain,$key,$value,$user=0) {
		$sql="select * from `setting` where ".
		"`domain`=".Database::text($domain)." and ".
		"`subdomain`=".Database::text($subdomain)." and ".
		"`key`=".Database::text($key)." and ".
		"`user_id`=".Database::int($user);
		if ($row = Database::selectFirst($sql)) {
			$sql="update `setting` set `value`=".Database::text($value)." where `id`=".$row['id'];
			return Database::update($sql);
		} else {
			$sql="insert into `setting` (`domain`,`subdomain`,`key`,`value`,`user_id`) values (".
			Database::text($domain).",".
			Database::text($subdomain).",".
			Database::text($key).",".
			Database::text($value).",".
			Database::int($user).
			")";
			return (Database::insert($sql)!==false);
		}
	}
	
	function setServiceSetting($service,$key,$value) {
		SettingService::setSetting('service',$service,$key,$value,InternalSession::getUserId());
	}
	
	function getServiceSetting($service,$key) {
		return SettingService::getSetting('service',$service,$key,InternalSession::getUserId());
	}
	
	/**
	 * @static
	 */
	function getSetting($domain,$subdomain,$key,$user=0) {
		$sql="select * from `setting` where ".
		"`domain`=".Database::text($domain)." and ".
		"`subdomain`=".Database::text($subdomain)." and ".
		"`key`=".Database::text($key)." and ".
		"`user_id`=".Database::int($user);
		if ($row = Database::selectFirst($sql)) {
			return $row['value'];
		} else {
			return NULL;
		}
	}
	
	function getOnlineObjectsUrl() {
		return SettingService::getSetting('system','onlineobjects','url');
	}
	
	function setOnlineObjectsUrl($value) {
		return SettingService::setSetting('system','onlineobjects','url',$value);
	}
	
	function getSharedSecret() {
		return SettingService::getSetting('system','security','sharedsecret');
	}
	
	function setSharedSecret($value) {
		return SettingService::setSetting('system','security','sharedsecret',$value);
	}
}
?>