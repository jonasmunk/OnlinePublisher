<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class PhoneNumber extends Object {
	var $number;
	var $context;
	var $containingObjectId=0;

	function PhoneNumber() {
		parent::Object('phonenumber');
	}

	function toUnicode() {
		parent::toUnicode();
		$this->number = mb_convert_encoding($this->number, "UTF-8","ISO-8859-1");
	}
	
	function setNumber($number) {
		$this->title = $number;
		$this->number = $number;
	}

	function getNumber() {
		return $this->number;
	}
	
	function setContext($context) {
		$this->context = $context;
	}

	function getContex() {
		return $this->context;
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
		$sql = "select object.id from phonenumber,object where object.id=phonenumber.object_id";
		if (isset($options['containingObjectId'])) {
			$sql.=" and phonenumber.containing_object_id=".$options['containingObjectId'];
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
			$list[] = PhoneNumber::load($id);
		}
		return $list;
	}
	

    /////////////////////////// Persistence ////////////////////////

	function load($id) {
		$sql = "select containing_object_id,number,context".
		" from phonenumber where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new PhoneNumber();
			$obj->_load($id);
			$obj->number=$row['number'];
			$obj->context=$row['context'];
			$obj->containingObjectId=$row['containing_object_id'];
			return $obj;
		} else {
			return null;
		}
	}

	function sub_create() {
		$sql="insert into phonenumber (object_id,number,context,containing_object_id) values (".
		$this->id.
		",".Database::text($this->number).
		",".Database::text($this->context).
		",".Database::int($this->containingObjectId).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update phonenumber set ".
		"number=".Database::text($this->number).
		",context=".Database::text($this->context).
		",containing_object_id=".Database::int($this->containingObjectId).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<phonenumber xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</phonenumber>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from phonenumber where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/PhoneNumber';
	}
	
	function getIn2iGuiIcon() {
		return "common/phone";
	}
}
?>