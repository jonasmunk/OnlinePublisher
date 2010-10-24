<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

class AuthenticationService {
	
	function ensureSecurity() {
		$sql = "select object_id as id from user where secure=0";
		$ids = Database::getIds($sql);
		foreach ($ids as $id) {
			if ($user = User::load($id)) {
				$user->setPassword(AuthenticationService::encryptPassword($user->getPassword()));
				$user->setSecure(true);
				$user->save();
			}
		}
	}
	
	function setPassword($user,$password) {
		$user->setPassword(AuthenticationService::encryptPassword($password));
		$user->setSecure(true);
	}
	
	function encryptPassword($str) {
		return sha1($str);
	}
	
	function getInternalUser($username,$password) {
		return AuthenticationService::getUser($username,$password,true);
	}
	
	function getExternalUser($username,$password) {
		return AuthenticationService::getUser($username,$password,false);
	}
	
	function getUser($username,$password,$internal=null) {
    	$sql="select object_id as id from user where ".
			" username=".Database::text($username)." and ((secure=0 and password=".Database::text($password).") or (secure=1 and password=".Database::text(AuthenticationService::encryptPassword($password))."))";
		if ($internal===true) {
			$sql.=" and internal=1";
		} else if ($internal===false) {
			$sql.=" and external=1";
		}
		//Log::debug($sql);
		if ($row = Database::selectFirst($sql)) {
			return User::load($row['id']);
		}
	}
}