<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
*/

require_once($basePath.'Editor/Classes/Object.php');

/**
 * Customer class
 *
 * A customer is a person who has or can buy something from the website
 * @package OnlinePublisher
 * @subpackage Classes
*/
class Customer extends Object {
	var $firstname;
	var $middlename;
	var $surname;
	var $email;
	var $phone;
	var $streetname;
	var $zipcode;
	var $city;
	var $country;
	
	function Customer() {
		parent::Object('customer');
	}
	
	function setFirstname($firstname) {
		$this->firstname = $firstname;
		$this->_updateTitle();
	}
	
	function getFirstname() {
		return $this->firstname;
	}
	
	function setMiddlename($middlename) {
		$this->middlename = $middlename;
		$this->_updateTitle();
	}
	
	function getMiddlename() {
		return $this->middlename;
	}
	
	function setSurname($surname) {
		$this->surname = $surname;
		$this->_updateTitle();
	}
	
	function getSurname() {
		return $this->surname;
	}
	
	function _updateTitle() {
		$title = '';
		if ($this->firstname!='') {
			$title.= $this->firstname;
		}
		if ($this->middlename!='') {
			if ($title!='') $title.=' ';
			$title.= $this->middlename;
		}
		if ($this->surname!='') {
			if ($title!='') $title.=' ';
			$title.= $this->surname;
		}
		$this->title = $title;
	}
	
	
	function setEmail($email) {
		$this->email = $email;
	}
	
	function getEmail() {
		return $this->email;
	}
	
	function setPhone($phone) {
		$this->phone = $phone;
	}
	
	function getPhone() {
		return $this->phone;
	}
	
	function setStreetname($streetname) {
		$this->streetname = $streetname;
	}
	
	function getStreetname() {
		return $this->streetname;
	}
	
	function setZipcode($zipcode) {
		$this->zipcode = $zipcode;
	}
	
	function getZipcode() {
		return $this->zipcode;
	}
	
	function setCity($city) {
		$this->city = $city;
	}
	
	function getCity() {
		return $this->city;
	}
	
	function setCountry($country) {
		$this->country = $country;
	}
	
	function getCountry() {
		return $this->country;
	}
	
	function load($id) {
		$obj = new Customer();
		$obj->_load($id);
		$sql = "select * from customer where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->firstname=$row['firstname'];
			$obj->middlename=$row['middlename'];
			$obj->surname=$row['surname'];
			$obj->email=$row['email'];
			$obj->phone=$row['phone'];
			$obj->streetname=$row['streetname'];
			$obj->zipcode=$row['zipcode'];
			$obj->city=$row['city'];
			$obj->country=$row['country'];
		}
		return $obj;
	}
	
	function sub_create() {
		$sql = "insert into customer (object_id,firstname,middlename,surname,".
				"email, phone, streetname , zipcode , city ,".
				"country ) values (".
				$this->id.
				",".sqlText($this->firstname).
				",".sqlText($this->middlename).
				",".sqlText($this->surname).
				",".sqlText($this->email).
				",".sqlText($this->phone).
				",".sqlText($this->streetname).
				",".sqlText($this->zipcode).
				",".sqlText($this->city).
				",".sqlText($this->country).
				")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql="update customer set".
		" firstname=".sqlText($this->firstname).
		",middlename=".sqlText($this->middlename).
		",surname=".sqlText($this->surname).
		",email=".sqlText($this->email).
		",phone=".sqlText($this->phone).
		",streetname=".sqlText($this->streetname).
		",zipcode=".sqlText($this->zipcode).
		",city=".sqlText($this->city).
		",country=".sqlText($this->country).
		" where object_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_publish() {
	
		$data = '<customer xmlns="'.parent::_buildnamespace('1.0').'">';
		if ($this->firstname!='') {
			$data.='<firstname>'.$this->firstname.'</firstname>';
		}
		if ($this->middlename!='') {
			$data.='<middlename>'.$this->middlename.'</middlename>';
		}
		if ($this->surname!='') {
			$data.='<surname>'.$this->surname.'</surname>';
		}
		if ($this->email!='') {
			$data.='<email>'.$this->email.'</email>';
		}
		if ($this->phone!='') {
			$data.='<phone>'.$this->phone.'</phone>';
		}
		if ($this->streetname!='') {
			$data.='<streetname>'.$this->streetname.'</streetname>';
		}
		if ($this->zipcode!='') {
			$data.='<zipcode>'.$this->zipcode.'</zipcode>';
		}
		if ($this->city!='') {
			$data.='<city>'.$this->city.'</city>';
		}
		if ($this->country!='') {
			$data.='<country>'.$this->country.'</country>';
		}
		$data.='</customer>';
		return $data;		
		
	}
	
	function sub_remove() {
		$sql = "delete from customer where object_id=".$this->id;
		Database::delete($sql);
	}
}
?>