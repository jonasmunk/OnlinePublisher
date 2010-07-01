<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class User extends Object {
	var $username;
	var $password;
	var $email;
	var $internal = false;
	var $external = false;
	var $administrator = false;

	function User() {
		parent::Object('user');
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

	function load($id) {
		$obj = new User();
		$obj->_load($id);
		$sql = "select * from user where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->username=$row['username'];
			$obj->password=$row['password'];
			$obj->email=$row['email'];
			$obj->internal=($row['internal']==1);
			$obj->external=($row['external']==1);
			$obj->administrator=($row['administrator']==1);
		}
		return $obj;
	}

	function sub_create() {
		$sql="insert into user (object_id,username,password,email,internal,external,administrator) values (".
		$this->id.
		",".sqlText($this->username).
		",".sqlText($this->password).
		",".sqlText($this->email).
		",".sqlBoolean($this->internal).
		",".sqlBoolean($this->external).
		",".sqlBoolean($this->administrator).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update user set ".
		"username=".sqlText($this->username).
		",password=".sqlText($this->password).
		",email=".sqlText($this->email).
		",internal=".sqlBoolean($this->internal).
		",external=".sqlBoolean($this->external).
		",administrator=".sqlBoolean($this->administrator).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<user xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<username>'.encodeXML($this->username).'</username>'.
		'</user>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from user where object_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from securityzone_user where user_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from user_permission where user_id=".$this->id;
		Database::delete($sql);
	}
}
?>