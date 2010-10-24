<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Page.php');

Object::$schema['securityzone'] = array(
	'authenticationPageId'   => array('type'=>'int','column'=>'authentication_page_id')
);
class SecurityZone extends Object {
	var $authenticationPageId;

	function SecurityZone() {
		parent::Object('securityzone');
	}
	
	function load($id) {
		return Object::get($id,'securityzone');
	}
	
	function setAuthenticationPageId($id) {
		$this->authenticationPageId = $id;
	}
	
	function getAuthenticationPageId() {
		return $this->authenticationPageId;
	}

	function removeMore() {
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