<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Page.php');

class SecurityZone extends Object {
	var $authenticationPageId;

	function SecurityZone() {
		parent::Object('securityzone');
	}
	
	function setAuthenticationPageId($id) {
		$this->authenticationPageId = $id;
	}
	
	function getAuthenticationPageId() {
		return $this->authenticationPageId;
	}

	function load($id) {
		$obj = new SecurityZone();
		$obj->_load($id);
		$sql = "select * from securityzone where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->authenticationPageId=$row['authentication_page_id'];
		}
		return $obj;
	}

	function sub_create() {
		$sql = "insert into securityzone (object_id,authentication_page_id) values (".$this->id.",".sqlInt($this->authenticationPageId).")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update securityzone set ".
		"authentication_page_id=".sqlInt($this->authenticationPageId).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_remove() {
		$sql = "delete from securityzone where object_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from securityzone_page where securityzone_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from securityzone_user where securityzone_id=".$this->id;
		Database::delete($sql);
		Page::updateSecureStateOfAllPages();
	}
	
	/***** Users *****/
	
	function addUser($userId) {
		$sql = "insert into securityzone_user (securityzone_id,user_id) values (".$this->id.",".$userId.")";
		Database::insert($sql);
	}
	
	function removeUser($userId) {
		$sql = "delete from securityzone_user where securityzone_id=".$this->id." and user_id=".$userId;
		Database::delete($sql);
	}
}
?>