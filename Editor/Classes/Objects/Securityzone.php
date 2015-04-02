<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Securityzone'] = [
	'table' => 'securityzone',
	'properties' => [
    	'authenticationPageId'   => ['type'=>'int','column'=>'authentication_page_id', 'relation' => ['class' => 'Page', 'property' => 'id']]
	]
];

class Securityzone extends Object {
	var $authenticationPageId;
    
    static $TYPE = 'securityzone';

	function Securityzone() {
		parent::Object(Securityzone::$TYPE);
	}
	
	static function load($id) {
		return Object::get($id,'securityzone');
	}
	
	function setAuthenticationPageId($id) {
		$this->authenticationPageId = $id;
	}
	
	function getAuthenticationPageId() {
		return $this->authenticationPageId;
	}

	function removeMore() {
		$sql = "DELETE from securityzone_page where securityzone_id=@int(id)";
		Database::delete($sql,['id'=>$this->id]);

		$sql = "DELETE from securityzone_user where securityzone_id=@int(id)";
		Database::delete($sql,['id'=>$this->id]);

		PageService::updateSecureStateOfAllPages();
	}
	
	/***** Users *****/
	
	function addUser($userId) {
		$sql = "INSERT into securityzone_user (securityzone_id,user_id) values (@int(zoneId),@int(userId))";
		Database::insert($sql,['zoneId'=>$this->id,'userId'=>$userId]);
	}
	
	function removeUser($userId) {
		$sql = "DELETE from securityzone_user where securityzone_id=@int(zoneId) and user_id=@int(userId)";
		Database::delete($sql,['zoneId'=>$this->id,'userId'=>$userId]);
	}
}
?>