<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Services/AuthenticationService.php');
require_once($basePath.'Editor/Classes/User.php');

class ExternalSession {
	
	function logIn($username,$password) {
		
		if ($user = AuthenticationService::getExternalUser($username,$password)) {
			$_SESSION['external.user']=array('id'=>$user->getId(),'username'=>$user->getUsername(),'title'=>$user->getTitle());
			return $user;
		}
		else {
			return false;
		}
	}

	function logOut() {
		unset($_SESSION['external.user']);
	}

	function getUser() {
		if (isset($_SESSION['external.user'])) {
			return $_SESSION['external.user'];
		}
		else {
			return false;
		}
	}
}