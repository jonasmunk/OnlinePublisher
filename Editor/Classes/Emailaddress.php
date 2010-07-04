<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class EmailAddress extends Object {
	var $address;
	var $containingObjectId=0;

	function EmailAddress() {
		parent::Object('emailaddress');
	}

	function toUnicode() {
		parent::toUnicode();
		$this->address = mb_convert_encoding($this->address, "UTF-8","ISO-8859-1");
	}
	
	function setAddress($address) {
		$this->title = $address;
		$this->address = $address;
	}

	function getAddress() {
		return $this->address;
	}

	function setContainingObjectId($id) {
		$this->containingObjectId = $id;
	}

	function getContainingObjectId() {
		return $this->containingObjectId;
	}
	
	function search($options = null) {
		if (!is_array($options)) {
			$options = array();
		}
		$sql = "select object.id from emailaddress,object where object.id=emailaddress.object_id";
		if (isset($options['containingObjectId'])) {
			$sql.=" and emailaddress.containing_object_id=".$options['containingObjectId'];
		}
		$sql.=" order by object.title";
		$result = Database::select($sql);
		$ids = array();
		while ($row = Database::next($result)) {
			$ids[] = $row['id'];
		}
		Database::free($result);
		
		$list = array();
		foreach ($ids as $id) {
			$list[] = EmailAddress::load($id);
		}
		return $list;
	}
	

    /////////////////////////// Persistence ////////////////////////

	function load($id) {
		$sql = "select containing_object_id,address".
		" from emailaddress where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new EmailAddress();
			$obj->_load($id);
			$obj->address=$row['address'];
			$obj->containingObjectId=$row['containing_object_id'];
			return $obj;
		} else {
			return null;
		}
	}

	function sub_create() {
		$sql="insert into emailaddress (object_id,address,containing_object_id) values (".
		$this->id.
		",".Database::text($this->address).
		",".sqlInt($this->containingObjectId).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update emailaddress set ".
		"address=".Database::text($this->address).
		",containing_object_id=".sqlInt($this->containingObjectId).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<emailaddress xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</emailaddress>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from emailaddress where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/EmailAddress';
	}
	
	function getIn2iGuiIcon() {
		return "common/email";
	}
}
?>