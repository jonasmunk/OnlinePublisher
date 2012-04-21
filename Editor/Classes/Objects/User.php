<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

Object::$schema['user'] = array(
	'username'   => array('type'=>'text'),
	'password'  => array('type'=>'text'),
	'email'  => array('type'=>'text'),
	'language'  => array('type'=>'language'),
	'internal'  => array('type'=>'boolean'),
	'external'  => array('type'=>'boolean'),
	'administrator'  => array('type'=>'boolean'),
	'secure'  => array('type'=>'boolean')
);
class User extends Object {
	
	var $username;
	var $password;
	var $email;
	var $language;
	var $internal = false;
	var $external = false;
	var $administrator = false;
	var $secure = false;

	function User() {
		parent::Object('user');
	}
	
	function load($id) {
		return Object::get($id,'user');
	}

	function getIn2iGuiIcon() {
		return 'common/user';
	}

	function setUsername($username) {
		$this->username = $username;
	}

	function getUsername() {
		return $this->username;
	}

	function setPassword($pass) {
		$this->password = $pass;
	}

	function getPassword() {
		return $this->password;
	}

	function setEmail($email) {
		$this->email = $email;
	}

	function getEmail() {
		return $this->email;
	}

	function setInternal($internal) {
		$this->internal = $internal;
	}

	function getInternal() {
		return $this->internal;
	}

	function setExternal($external) {
		$this->external = $external;
	}

	function getExternal() {
		return $this->external;
	}

	function setAdministrator($admin) {
		$this->administrator = $admin;
	}

	function getAdministrator() {
		return $this->administrator;
	}

	function setLanguage($language) {
		$this->language = $language;
	}

	function getLanguage() {
		return $this->language;
	}
	
	function setSecure($secure) {
	    $this->secure = $secure;
	}

	function getSecure() {
	    return $this->secure;
	}
	
	function removeMore() {
		$sql = "delete from securityzone_user where user_id=".Database::int($this->id);
		Database::delete($sql);
		$sql = "delete from user_permission where user_id=".Database::int($this->id);
		Database::delete($sql);
	}

	function sub_publish() {
		$data =
		'<user xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<username>'.StringUtils::escapeXML($this->username).'</username>'.
		'</user>';
		return $data;
	}
}
?>