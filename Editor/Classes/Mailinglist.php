<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Mailinglist extends Object {

	function Mailinglist() {
		parent::Object('mailinglist');
	}
	
	function getIn2iGuiIcon() {
		return 'common/email';
	}

	function load($id) {
		$sql = "select * from mailinglist where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new Mailinglist();
			$obj->_load($id);
			return $obj;
		} else {
			return null;
		}
	}

	function sub_create() {
		$sql = "insert into mailinglist (object_id) values (".$this->id.")";
		Database::insert($sql);
	}

	function sub_update() {
	}

	function sub_remove() {
		$sql = "delete from person_mailinglist where mailinglist_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from part_mailinglist_mailinglist where mailinglist_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from mailinglist where object_id=".$this->id;
		Database::delete($sql);
	}

	function sub_publish() {
		$data =
		'<mailinglist xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</mailinglist>';
		return $data;
	}
	
	function search($options = null) {
		if (!is_array($options)) {
			$options = array();
		}
		$sql = "select object.id from mailinglist,object where object.id=mailinglist.object_id";
		$sql.=" order by object.title";
		
		$result = Database::select($sql);
		$list = array();
		while ($row = Database::next($result)) {
			$list[] = Mailinglist::load($row['id']);
		}
		Database::free($result);
		return $list;
	}
	
	function getEmails() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Emailaddress.php');
		if (!is_array($options)) {
			$options = array();
		}
		$sql = "select emailaddress.object_id as id from emailaddress,person_mailinglist where emailaddress.containing_object_id=person_mailinglist.person_id and person_mailinglist.mailinglist_id=".$this->id;
		error_log($sql);
		$result = Database::select($sql);
		$list = array();
		while ($row = Database::next($result)) {
			$list[] = EmailAddress::load($row['id']);
		}
		Database::free($result);
		return $list;
	}
	
}
?>