<?
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Tool.php');
class InternalSession {
    
    function InternalSession() {
    }
    
    function logIn($username,$password) {
    	$sql="select object_id,username,administrator from user where internal=1 and username=".Database::text($username)." and password=".Database::text($password);
    	if ($row = Database::selectFirst($sql)) {
    	    startSession();
    		$_SESSION['core.user.id']=$row['object_id'];
    		$_SESSION['core.user.username']=$row['username'];
    		$_SESSION['core.user.administrator']=($row['administrator']==1);
    		InternalSession::registerActivity();
    		Log::logUser('login','');
    		return true;
    	} else {
    	    return false;
    	}
    }
    
    function logOut() {
		session_start();
		Log::logUser('logout','');
		$_SESSION['core.user.id']=0;
		$_SESSION['core.user.username']=null;
		$_SESSION['core.user.administrator']=0;
    }
    
    // Static
    function registerActivity() {
        $_SESSION['core.user.lastaccesstime']=time();
    }

	/**
	 * Get the ID of the active internal user
	 * @return int ID of the user
	 * @static
	 */
	function getUserId() {
		if (isset($_SESSION['core.user.id'])) {
			return $_SESSION['core.user.id'];
		}
		else {
			return -1;
		}
	}

	/**
	 * Sets the ID of the active page
	 * @param int $id The pages ID
	 */
	function setPageId($id) {
		$_SESSION['core.page.id']=$id;
	}

	/**
 	* Get the ID of the active page
	 * @return The id of active page, -1 of not yet set
	 */
	function getPageId() {
		if (isset($_SESSION['core.page.id'])) {
			return $_SESSION['core.page.id'];
		}
		else {
			return -1;
		}
	}

	/**
	 * Get the username of the active internal user
	 * @return string Username of the user
	 */
	function getUsername() {
		if (isset($_SESSION['core.user.username'])) {
			return $_SESSION['core.user.username'];
		}
		else {
			return null;
		}
	}
    
    function isLoggedIn() {
        return (isset($_SESSION['core.user.id']) && $_SESSION['core.user.id']>0);
    }
    
    function isTimedOut() {
        return (time()-($_SESSION['core.user.lastaccesstime'])>86400);
    }
    
    function isAdministrator() {
        return $_SESSION['core.user.administrator'];
    }
    
    function getPermissions($type) {
        $permissions = array();
        $userId = InternalSession::getUserId();
        $sql = "select entity_id from user_permission where user_id=".$userId." and entity_type='tool'";
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $permissions[] = $row['entity_id'];
        }
        Database::free($result);
        return $permissions;
    }

    function getToolsByCategory($cat) {
        $tools = Tool::getToolsByCategory($cat);
        if (InternalSession::isAdministrator()) {
            return $tools;
        } else {
            $out = array();
            $perms = InternalSession::getPermissions('tool');
            foreach ($tools as $tool) {
                if (in_array($tool['id'],$perms)) {
                    $out[] = $tool;
                }
            }
            return $out;
        }
    }

    function getTools() {
        $tools = Tool::getTools();
        if (InternalSession::isAdministrator()) {
            return $tools;
        } else {
            $out = array();
            $perms = InternalSession::getPermissions('tool');
            foreach ($tools as $tool) {
                if (in_array($tool['id'],$perms)) {
                    $out[] = $tool;
                }
            }
            return $out;
        }
    }
}
?>