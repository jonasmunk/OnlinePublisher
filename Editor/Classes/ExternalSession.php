<?
require_once($basePath.'Editor/Classes/Database.php');

class ExternalSession {
	
	function logIn($username,$password) {
		$sql = "select * from user,object where object.id=user.object_id and username=".Database::text($username)." and password=".Database::text($password)." and external=1";
		if ($row=Database::selectFirst($sql)) {
			$user = array('id'=>$row['id'],'username'=>$row['username'],'title'=>$row['title']);
			$_SESSION['external.user']=$user;
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