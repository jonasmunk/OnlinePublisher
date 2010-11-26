<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */

class RemoteData {
	
	var $file;
	var $age;
	
	function setFile($file) {
	    $this->file = $file;
	}

	function getFile() {
	    return $this->file;
	}
	
	function setAge($age) {
	    $this->age = $age;
	}

	function getAge() {
	    return $this->age;
	}
	
}