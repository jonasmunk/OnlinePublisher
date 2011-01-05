<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Libraries/nusoap/nusoap.php');

class RemotePublisher extends Object {
	var $url;

	function RemotePublisher() {
		parent::Object('remotepublisher');
	}

	function setUrl($url) {
		$this->url = $url;
	}

	function getUrl() {
		return $this->url;
	}

	/////////////////////// Persistence //////////////////////

	function load($id) {
		$obj = new RemotePublisher();
		$obj->_load($id);
		$sql = "select * from remotepublisher where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->url=$row['url'];
		}
		return $obj;
	}

	function sub_create() {
		$sql="insert into remotepublisher (object_id,url) values (".
		$this->id.
		",".Database::text($this->url).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update remotepublisher set ".
		"url=".Database::text($this->url).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<remotepublisher xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<url>'.StringUtils::escapeXML($this->url).'</url>'.
		'</remotepublisher>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from remotepublisher where object_id=".$this->id;
		Database::delete($sql);
	}
	
	//////////////////// Services ////////////////////////
	
	function getVersionNumber() {
		$client = new nusoapclient($this->url.'services/soap/');
		// Check for an error
		$err = $client->getError();
		if ($err) {
		    // Display the error
		    return 'Constructor error: ' . $err;
		}
		// Call the SOAP method
		$result = $client->call(
		    'getSystemVersion',														// method name
		    array(),    															// input parameters
		    'http://uri.in2isoft.com/onlinepublisher/services/',					// namespace
		    'http://uri.in2isoft.com/onlinepublisher/services/getSystemVersion'		// SOAPAction
		);
		if ($client->fault) {
		    return 'Fault: '.$result;
		} else {
		    // Check for errors
		    $err = $client->getError();
		    if ($err) {
		        return 'Error: '.$err;
		    } else {
		        return $result;		// Success
		    }
		}
	}
}
?>