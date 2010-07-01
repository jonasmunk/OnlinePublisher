<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class WeblogGroup extends Object {

	function WeblogGroup() {
		parent::Object('webloggroup');
	}
	
	function getIcon() {
		return 'Element/Folder';
	}
	
	function getIn2iGuiIcon() {
		return 'common/folder';
	}

	function load($id) {
		$obj = new WeblogGroup();
		$obj->_load($id);
		return $obj;
	}

	function sub_create() {
		$sql = "insert into webloggroup (object_id) values (".$this->id.")";
		Database::insert($sql);
	}

	function sub_update() {
	}

	function sub_remove() {
		$sql="delete from webloggroup_weblogentry where webloggroup_id=".$this->id;
		Database::delete($sql);
		$sql="delete from weblog_webloggroup where webloggroup_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from webloggroup where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/**
	 * @static
	 */
    function search($query = array()) {
        $out = array();
		if (isset($out['page'])) {
			$sql = "select webloggroup_id as id from weblog_webloggroup where page_id=".$out['page'];
		} else {
        	$sql = "select id from object,webloggroup where object.id=webloggroup.object_id order by object.title";
		}
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
			$out[] = WeblogGroup::load($row['id']);
        }
        Database::free($result);
        return $out;
    }
}
?>