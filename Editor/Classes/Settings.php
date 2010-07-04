<?
require_once($basePath.'Editor/Classes/InternalSession.php');

class Settings {

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
		Settings::setSetting('service',$service,$key,$value,InternalSession::getUserId());
	}
	
	function getServiceSetting($service,$key) {
		return Settings::getSetting('service',$service,$key,InternalSession::getUserId());
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
		return Settings::getSetting('system','onlineobjects','url');
	}
	
	function setOnlineObjectsUrl($value) {
		return Settings::setSetting('system','onlineobjects','url',$value);
	}
}
?>