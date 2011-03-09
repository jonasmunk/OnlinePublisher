<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class Person extends Object {
	var $firstname;
	var $middlename;
	var $surname;
	var $initials;
	var $nickname;
	var $jobtitle;
	var $sex=1;
	var $email_job;
	var $email_private;
	var $phone_job;
	var $phone_private;
	var $streetname;
	var $zipcode;
	var $city;
	var $country;
	var $webaddress;
	var $image_id;
	
	function Person() {
		parent::Object('person');
	}
	
	function toUnicode() {
		parent::toUnicode();
		$this->firstname = mb_convert_encoding($this->firstname, "UTF-8","ISO-8859-1");
		$this->middlename = mb_convert_encoding($this->middlename, "UTF-8","ISO-8859-1");
		$this->surname = mb_convert_encoding($this->surname, "UTF-8","ISO-8859-1");
		$this->initials = mb_convert_encoding($this->initials, "UTF-8","ISO-8859-1");
		$this->jobtitle = mb_convert_encoding($this->jobtitle, "UTF-8","ISO-8859-1");
		$this->nickname = mb_convert_encoding($this->nickname, "UTF-8","ISO-8859-1");
		$this->streetname = mb_convert_encoding($this->streetname, "UTF-8","ISO-8859-1");
		$this->city = mb_convert_encoding($this->city, "UTF-8","ISO-8859-1");
	}
	
	function setFullName($name) {
		$this->firstname='';
		$this->middlename='';
		$this->surname='';
		$names = preg_split('/\s+/', $name);
		if (count($names)>0) {
			$this->firstname = $names[0];
		}
		if (count($names)==2) {
			$this->surname = $names[1];
		}
		else if (count($names)>2) {
			$this->middlename = $names[1];
			for ($i=2; $i < count($names); $i++) {
				if (strlen($this->surname)>0) {
					$this->surname.=' ';
				}
				$this->surname.=$names[$i];
			}
		}
		$this->_updateTitle();
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
	
	function setInitials($initials) {
		$this->initials = $initials;
	}
	
	function getInitials() {
		return $this->initials;
	}
	
	function setNickname($nickname) {
		$this->nickname = $nickname;
	}
	
	function getNickname() {
		return $this->nickname;
	}
	
	function setJobtitle($jobtitle) {
		$this->jobtitle = $jobtitle;
	}
	
	function getJobtitle() {
		return $this->jobtitle;
	}
	
	function setSex($sex) {
		$this->sex = $sex;
	}
	
	function getSex() {
		return $this->sex;
	}
	
	function setEmail_job($email_job) {
		$this->email_job = $email_job;
	}
	
	function getEmail_job() {
		return $this->email_job;
	}
	
	function setEmail_private($email_private) {
		$this->email_private = $email_private;
	}
	
	function getEmail_private() {
		return $this->email_private;
	}
	
	function setPhone_job($phone_job) {
		$this->phone_job = $phone_job;
	}
	
	function getPhone_job() {
		return $this->phone_job;
	}
	
	function setPhone_private($phone_private) {
		$this->phone_private = $phone_private;
	}
	
	function getPhone_private() {
		return $this->phone_private;
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
	
	function setWebaddress($webaddress) {
		$this->webaddress = $webaddress;
	}
	
	function getWebaddress() {
		return $this->webaddress;
	}
	
	function setImage_id($image_id) {
		$this->image_id = $image_id;
	}
	
	function getImage_id() {
		return $this->image_id;
	}
	
	function getMailinglistIds() {
		$sql = "select mailinglist_id as id from person_mailinglist where person_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function updateMailinglistIds($ids) {
		$ids = Object::getValidIds($ids);
		$sql = "delete from person_mailinglist where person_id=".$this->id;
		Database::delete($sql);
		foreach ($ids as $id) {
			$sql = "insert into person_mailinglist (mailinglist_id,person_id) values (".$id.",".$this->id.")";
			Database::insert($sql);
		}
	}
	
	function getGroupIds() {
		$sql = "select persongroup_id as id from persongroup_person where person_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function updateGroupIds($ids) {
		$ids = Object::getValidIds($ids);
		$sql = "delete from persongroup_person where person_id=".$this->id;
		Database::delete($sql);
		foreach ($ids as $id) {
			$sql = "insert into persongroup_person (persongroup_id,person_id) values (".$id.",".$this->id.")";
			Database::insert($sql);
		}
	}
	
	function addGroupId($id) {
		
		$sql = "delete from persongroup_person where person_id=".$this->id." and persongroup_id=".$id;
		Database::delete($sql);
		$sql = "insert into persongroup_person (persongroup_id,person_id) values (".$id.",".$this->id.")";
		Database::insert($sql);
	}
	
	function search($options = null) {
		if (!is_array($options)) {
			$options = array();
		}
		$sql = "select object.id from person,object";
		if (isset($options['mailinglist'])) {
			$sql.=",person_mailinglist";
		}
		if (isset($options['persongroup'])) {
			$sql.=",persongroup_person";
		}
		$sql.=" where object.id=person.object_id";
		if (isset($options['mailinglist'])) {
			$sql.=" and person_mailinglist.person_id = object.id and person_mailinglist.mailinglist_id=".$options['mailinglist'];
		}
		if (isset($options['persongroup'])) {
			$sql.=" and persongroup_person.person_id = object.id and persongroup_person.persongroup_id=".$options['persongroup'];
		}
		$sql.=" order by object.title";
		$result = Database::select($sql);
		$list = array();
		while ($row = Database::next($result)) {
			$list[] = Person::load($row['id']);
		}
		Database::free($result);
		return $list;
	}

    function find($query = array()) {
    	$parts = array();
		$parts['columns'] = 'object.id';
		$parts['tables'] = 'person,object';
		$parts['limits'] = array();
		$parts['ordering'] = 'object.title';
		$parts['direction'] = $query['direction'];
		
		$parts['limits'][] = "object.id=person.object_id";
		if (isset($query['mailinglist'])) {
			$parts['tables'].=",person_mailinglist";
			$parts['limits'][] = "person_mailinglist.person_id = object.id";
			$parts['limits'][] = "person_mailinglist.mailinglist_id=".$query['mailinglist'];
		}
		if (isset($query['persongroup'])) {
			$parts['tables'].=",persongroup_person";
			$parts['limits'][] = "persongroup_person.person_id = object.id";
			$parts['limits'][] = "persongroup_person.persongroup_id=".$query['persongroup'];
		}
		if (isset($query['query'])) {
			$parts['limits'][]='`object`.`index` like '.Database::search($query['query']);
		}
		if ($query['sort']=='title') {
			$parts['ordering']="object.title";
		}
		
		$list = parent::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			$list['result'][] = Person::load($row['id']);
		}
		return $list;
	}
	
	function loadByEmail($email) {
		$sql = "select object.id from emailaddress, object where emailaddress.containing_object_id=object.id and object.type='person' and emailaddress.address=".Database::text($email);
		$row = Database::selectFirst($sql);
		if ($row) {
			return Person::load($row['id']);
		} else {
			return null;
		}
	}
	
	function load($id) {
		$sql = "select * from person where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new Person();
			$obj->_load($id);
			$obj->firstname=$row['firstname'];
			$obj->middlename=$row['middlename'];
			$obj->surname=$row['surname'];
			$obj->initials=$row['initials'];
			$obj->nickname=$row['nickname'];
			$obj->jobtitle=$row['jobtitle'];
			$obj->sex=$row['sex'];
			$obj->email_job=$row['email_job'];
			$obj->email_private=$row['email_private'];
			$obj->phone_job=$row['phone_job'];
			$obj->phone_private=$row['phone_private'];
			$obj->streetname=$row['streetname'];
			$obj->zipcode=$row['zipcode'];
			$obj->city=$row['city'];
			$obj->country=$row['country'];
			$obj->webaddress=$row['webaddress'];
			$obj->image_id=$row['image_id'];
			return $obj;
		} else {
			return null;
		}
	}
	
	function sub_create() {
		$sql = "insert into person (object_id,firstname,middlename,surname,initials,nickname,jobtitle,".
				"sex , email_job , email_private , phone_job , phone_private , streetname , zipcode , city ,".
				"country , webaddress , image_id ) values (".
				$this->id.
				",".Database::text($this->firstname).
				",".Database::text($this->middlename).
				",".Database::text($this->surname).
				",".Database::text($this->initials).
				",".Database::text($this->nickname).
				",".Database::text($this->jobtitle).
				",".Database::text($this->sex).
				",".Database::text($this->email_job).
				",".Database::text($this->email_private).
				",".Database::text($this->phone_job).
				",".Database::text($this->phone_private).
				",".Database::text($this->streetname).
				",".Database::text($this->zipcode).
				",".Database::text($this->city).
				",".Database::text($this->country).
				",".Database::text($this->webaddress).
				",".Database::text($this->image_id).
				")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql="update person set".
		" firstname=".Database::text($this->firstname).
		",middlename=".Database::text($this->middlename).
		",surname=".Database::text($this->surname).
		",initials=".Database::text($this->initials).
		",nickname=".Database::text($this->nickname).
		",jobtitle=".Database::text($this->jobtitle).
		",sex=".Database::text($this->sex).
		",email_job=".Database::text($this->email_job).
		",email_private=".Database::text($this->email_private).
		",phone_job=".Database::text($this->phone_job).
		",phone_private=".Database::text($this->phone_private).
		",streetname=".Database::text($this->streetname).
		",zipcode=".Database::text($this->zipcode).
		",city=".Database::text($this->city).
		",country=".Database::text($this->country).
		",webaddress=".Database::text($this->webaddress).
		",image_id=".Database::text($this->image_id).
		" where object_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_publish() {
	
		$data = '<person xmlns="'.parent::_buildnamespace('1.0').'">';
		if ($this->firstname!='') {
			$data.='<firstname>'.StringUtils::escapeXML($this->firstname).'</firstname>';
		}
		if ($this->middlename!='') {
			$data.='<middlename>'.StringUtils::escapeXML($this->middlename).'</middlename>';
		}
		if ($this->surname!='') {
			$data.='<surname>'.StringUtils::escapeXML($this->surname).'</surname>';
		}
		if ($this->initials!='') {
			$data.='<initials>'.StringUtils::escapeXML($this->initials).'</initials>';
		}
		if ($this->nickname!='') {
			$data.='<nickname>'.StringUtils::escapeXML($this->nickname).'</nickname>';
		}
		if ($this->jobtitle!='') {
			$data.='<jobtitle>'.StringUtils::escapeXML($this->jobtitle).'</jobtitle>';
		}
		if (isset($this->sex)) {
			$data.='<sex>'.($this->sex ? 'male' : 'female').'</sex>';
		}
		if ($this->email_job!='') {
			$data.='<email context="job">'.StringUtils::escapeXML($this->email_job).'</email>';
		}
		if ($this->email_private!='') {
			$data.='<email context="private">'.StringUtils::escapeXML($this->email_private).'</email>';
		}
		if ($this->phone_job!='') {
			$data.='<phone context="job">'.StringUtils::escapeXML($this->phone_job).'</phone>';
		}
		if ($this->phone_private!='') {
			$data.='<phone context="private">'.StringUtils::escapeXML($this->phone_private).'</phone>';
		}
		if ($this->streetname!='') {
			$data.='<streetname>'.StringUtils::escapeXML($this->streetname).'</streetname>';
		}
		if ($this->zipcode!='') {
			$data.='<zipcode>'.StringUtils::escapeXML($this->zipcode).'</zipcode>';
		}
		if ($this->city!='') {
			$data.='<city>'.StringUtils::escapeXML($this->city).'</city>';
		}
		if ($this->country!='') {
			$data.='<country>'.StringUtils::escapeXML($this->country).'</country>';
		}
		if ($this->webaddress!='') {
			$data.='<webaddress>'.StringUtils::escapeXML($this->webaddress).'</webaddress>';
		}
		if ($this->image_id>0) {
			$sql="select * from object where id=".$this->image_id;
			if ($img = Database::selectFirst($sql)) {
				$data.='<image>'.$img['data'].'</image>';
			}
		}
		
		
		$sql = "select address from emailaddress where containing_object_id=".$this->id;
		$result = Database::select($sql);
    	while ($row = Database::next($result)) {
			$data.='<email>'.StringUtils::escapeXML($row['address']).'</email>';
    	}
		Database::free($result);
		
		$sql = "select number,context from phonenumber where containing_object_id=".$this->id;
		$result = Database::select($sql);
    	while ($row = Database::next($result)) {
			$data.='<phone context="'.StringUtils::escapeXML($row['context']).'">'.StringUtils::escapeXML($row['number']).'</phone>';
    	}
		Database::free($result);

		$data.='</person>';
		return $data;		
		
	}
	
	function sub_remove() {
		$sql="delete from person_mailinglist where person_id=".$this->id;
		Database::delete($sql);
		$sql="delete from emailaddress where containing_object_id=".$this->id;
		Database::delete($sql);
		$sql="delete from phonenumber where containing_object_id=".$this->id;
		Database::delete($sql);
		$sql="delete from persongroup_person where person_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from person where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    if ($this->sex) {
	        return "Role/Male";
	    } else {
	        return "Role/Female";
	    }
	}
	
	function getIn2iGuiIcon() {
		return "common/person";
	}
	
	function updateEmailAddresses($new) {
		global $basePath;
		require_once($basePath.'Editor/Classes/Objects/Emailaddress.php');
		$mails = Query::after('emailaddress')->withProperty('containingObjectId',$this->getId())->get();
		$foundIds = array();
		foreach ($new as $email) { 
			if (isset($email->id) && $email->id>0) {
				$old = EmailAddress::load($email->id);
				if ($old==null) {
					$old = new EmailAddress();
				} else {
					$foundIds[] = $old->getId();
				}
			} else {
				$old = new EmailAddress();
			}
			if (strlen($email->address)>0) {
				$old->setAddress(Request::fromUnicode($email->address));
				$old->setContainingObjectId($this->getId());
				$old->save();
				$old->publish();
			} else if ($old->getId()>0) {
				$old->remove();
			}
		}
		foreach ($mails as $mail) {
			if (!in_array($mail->getId(),$foundIds)) {
				$mail->remove();
			}
		}
	}
	
	function updatePhoneNumbers($new) {
		global $basePath;
		require_once($basePath.'Editor/Classes/Phonenumber.php');
		$query = array('containingObjectId'=>$this->id);
		$numbers = PhoneNumber::search($query);
		$foundIds = array();
		foreach ($new as $number) { 
			if (isset($number->id) && $number->id>0) {
				$old = PhoneNumber::load($number->id);
				if ($old==null) {
					$old = new PhoneNumber();
				} else {
					$foundIds[] = $old->getId();
				}
			} else {
				$old = new PhoneNumber();
			}
			if (strlen($number->number)>0 || strlen($number->context)>0) {
				$old->setNumber(Request::fromUnicode($number->number));
				$old->setContext(Request::fromUnicode($number->context));
				$old->setContainingObjectId($this->getId());
				$old->save();
				$old->publish();
			} else if ($old->getId()>0) {
				$old->remove();
			}
		}
		foreach ($numbers as $mail) {
			if (!in_array($mail->getId(),$foundIds)) {
				$mail->remove();
			}
		}
	}
}
?>