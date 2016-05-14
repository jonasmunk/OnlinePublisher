<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class InternalSession {
    
	/**
	 * Starts a session in the appropriate way, should be used instead
	 * of calling session_start() directly
	 */
	static function startSession() {
		session_set_cookie_params(0);
		session_start();
	}
    
    static function logIn($username,$password) {
    	if ($user = AuthenticationService::getInternalUser($username,$password)) {
    	    InternalSession::startSession();
    		$_SESSION['core.user.id'] = $user->getId();
    		$_SESSION['core.user.username'] = $user->getUsername();
    		$_SESSION['core.user.administrator'] = $user->getAdministrator();
			InternalSession::setLanguage($user->getLanguage());
    		InternalSession::registerActivity();
    		Log::logUser('login','');
    		return true;
    	} else {
    	    return false;
    	}
    }
    
    static function logOut() {
		session_start();
		Log::logUser('logout','');
		$_SESSION['core.user.id']=0;
		$_SESSION['core.user.username']=null;
		$_SESSION['core.user.administrator']=0;
    }
    
    // Static
    static function registerActivity() {
        $_SESSION['core.user.lastaccesstime']=time();
    }

	static function getLanguage() {
		//return 'en';
		if (isset($_SESSION['core.user.language'])) {
			return $_SESSION['core.user.language'];
		}
		return 'da';
	}

	static function setLanguage($language) {
		if ($language=='da' || $language=='en') {
			$_SESSION['core.user.language'] = $language;
		} else {
			$_SESSION['core.user.language'] = 'da';
		}
	}

	/**
	 * Get the ID of the active internal user
	 * @return int ID of the user
	 * @static
	 */
	static function getUserId() {
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
	static function setPageId($id) {
		if (!is_int($id)) {
			Log::debug('Not an int: ' . gettype($id) . '('.$id.')');
		}
		$_SESSION['core.page.id'] = intval($id);
	}

	/**
 	* Get the ID of the active page
	 * @return The id of active page, -1 of not yet set
	 */
	static function getPageId() {
		if (isset($_SESSION['core.page.id'])) {
			return intval($_SESSION['core.page.id']);
		}
		else {
			return -1;
		}
	}

	/**
	 * Sets the design of the active page
	 * @param string $unique The unique name of the design
	 */
	static function setPageDesign($unique) {
		$_SESSION['core.page.design']=$unique;
	}

	/**
	 * Gets the design of the active page (if set) or a special sticky design
	 * @return string The design of the active page if set, false otherwise
	 */
	static function getPageDesign() {
		if (isset($_SESSION['debug.design'])) {
			return $_SESSION['debug.design'];
		}
		elseif (isset($_SESSION['core.page.design'])) {
			return $_SESSION['core.page.design'];
		}
		else {
			return false;
		}
	}

	/**
	 * Get the username of the active internal user
	 * @return string Username of the user
	 */
	static function getUsername() {
		if (isset($_SESSION['core.user.username'])) {
			return $_SESSION['core.user.username'];
		}
		else {
			return null;
		}
	}
    
    static function isLoggedIn() {
        return (isset($_SESSION['core.user.id']) && $_SESSION['core.user.id']>0);
    }
    
    static function isTimedOut() {
        return (time()-($_SESSION['core.user.lastaccesstime'])>86400);
    }
    
    static function isAdministrator() {
        return $_SESSION['core.user.administrator'];
    }
    
    static function getPermissions($type) {
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


	static function getToolSessionVar($tool,$key,$default=NULL) {
		if (isset($_SESSION['tools.'.$tool.'.'.$key])) {
			return $_SESSION['tools.'.$tool.'.'.$key];
		}
		else {
			InternalSession::setToolSessionVar($tool,$key,$default);
			return $default;
		}
	}

	static function switchToolSessionVar($tool,$key) {
		InternalSession::setToolSessionVar($tool,$key,!InternalSession::getToolSessionVar($tool,$key));
	}

	static function getRequestToolSessionVar($tool,$key,$query,$default=NULL) {
		if (Request::exists($query)) {
			InternalSession::setToolSessionVar($tool,$key,Request::getString($query));
		}
		return InternalSession::getToolSessionVar($tool,$key,$default);
	}

	static function setToolSessionVar($tool,$key,$value) {
		$_SESSION['tools.'.$tool.'.'.$key]=$value;
	}
	
	static function getSessionCacheVar($key) {
		if (isset($_SESSION['cache.'.$key])) {
			return $_SESSION['cache.'.$key];
		}
		else {
			return null;
		}
	}

	static function setSessionCacheVar($key,$value) {
		$_SESSION['cache.'.$key]=$value;
	}
	
	
	/**************** services ****************/


	static function getRequestServiceSessionVar($service,$key,$query,$default=NULL) {
		if (Request::exists($query)) {
			InternalSession::setServiceSessionVar($service,$key,Request::getString($query));
		}
		return InternalSession::getServiceSessionVar($service,$key,$default);
	}

	static function getServiceSessionVar($service,$key,$default=NULL) {
		if (isset($_SESSION['services.'.$service.'.'.$key])) {
			return $_SESSION['services.'.$service.'.'.$key];
		}
		else {
			InternalSession::setServiceSessionVar($service,$key,$default);
			return $default;
		}
	}

	static function setServiceSessionVar($service,$key,$value) {
		$_SESSION['services.'.$service.'.'.$key]=$value;
	}
}
?>