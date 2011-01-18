<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/User.php');
require_once($basePath.'Editor/Classes/Services/MailService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

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
	
	function getUserByEmailOrUsername($emailOrUsername) {
		if (StringUtils::isBlank($emailOrUsername)) {
			return null;
		}
		$sql="select object_id from user where username=".Database::text($emailOrUsername)." or email=".Database::text($emailOrUsername);
		if ($row = Database::selectFirst($sql)) {
			$id = intval($row['object_id']);
			$user = User::load($id);
			if (!$user) {
				Log::debug('AuthenticationService: User with ID='.$id.' could not be loaded');
			}
			return $user;
		}
		return null;
	}

	function createValidationSession($user) {
	    global $baseUrl;
	    $unique = md5(uniqid(rand(), true));
	    $limit = time() + 60*60; // 1 hour into future
    
	    $sql = "insert into email_validation_session (`unique`,`user_id`,`email`,`timelimit`)".
	    " values (".
	    Database::text($unique).",".$user->getId().",".Database::text($user->getEmail()).",".Database::datetime($limit).
	    ")";
	    Database::insert($sql);
	    // Create the email
	    $body = "Klik på følgende link for at ændre dit kodeord til brugeren \"".$user->getUsername()."\": \n".
	    $baseUrl."Editor/LostPassword.php?id=".$unique;
	    return MailService::send($email,$userName,"OnlinePublisher - ændring af kodeord",$body);
	}
}