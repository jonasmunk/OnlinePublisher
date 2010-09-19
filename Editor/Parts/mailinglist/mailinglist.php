<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Mailinglist
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');

class PartMailinglist extends LegacyPartController {
	
	function PartMailinglist($id=0) {
		parent::LegacyPartController('mailinglist');
		$this->id = $id;
	}
	
	function sub_isDynamic() {
		return true;
	}
	
	function sub_display($context) {
		$data='';
		$sql = "select * from part_mailinglist where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data=
			'<div class="Part-mailinglist">'.
			$this->render().
			'</div>';
		}
		return $data;
	}
	
	function getMailingListIds() {
		$sql = "select mailinglist_id as id from part_mailinglist_mailinglist where part_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function sub_editor($context) {
		$sql = "select * from part_mailinglist where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$ids = $this->getMailingListIds();
			return
			$this->render().
			'<input type="hidden" name="mailinglists" value="'.implode(',',$ids).'"/>';
		} else {
			return '';
		}
	}
	
	function sub_create() {
		$sql = "insert into part_mailinglist (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_mailinglist where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$lists = explode(',',Request::getString('mailinglists'));
		$sql = "delete from part_mailinglist_mailinglist where part_id=".$this->id;
		Database::delete($sql);
		foreach ($lists as $list) {
			$sql = "insert into part_mailinglist_mailinglist (part_id,mailinglist_id) values (".Database::int($this->id).",".Database::int($list).")";
			Database::update($sql);
		}
	}
	
	function sub_build($context) {
		$action = Request::getUnicodeString($this->id.'_action');
		$subscribe_error = '';
		$subscribe_body = '';
		$unsubscribe_error = '';
		$unsubscribe_body = '';
		if ($action=='subscribe') {
			$name = Request::getUnicodeString($this->id.'_name');
			$email = Request::getUnicodeString($this->id.'_email');
			if ($name=='') {
				$subscribe_error = 'noname';
			} else if ($email=='') {
				$subscribe_error = 'noemail';
			} else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
				$subscribe_error = 'invalidemail';
			}
			if ($subscribe_error!='') {
				$subscribe_body .= '<value key="name" value="'.encodeXML($name).'"/>';
				$subscribe_body .= '<value key="email" value="'.encodeXML($email).'"/>';
			} else {
				$this->subscribe($name,$email);
				$subscribe_body .= '<success/>';
			}
		} else if ($action=='unsubscribe') {
			$email = Request::getUnicodeString($this->id.'_email');
			if ($email=='') {
				$unsubscribe_error = 'noemail';
				$unsubscribe_body .= '<value key="email" value="'.encodeXML($email).'"/>';
			} else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
				$unsubscribe_error = 'invalidemail';
				$unsubscribe_body .= '<value key="email" value="'.encodeXML($email).'"/>';
			} else {
				if ($this->unsubscribe($email)) {
					$unsubscribe_body .= '<success/>';
				} else {
					$unsubscribe_error = 'notsubscribed';
					$unsubscribe_body .= '<value key="email" value="'.encodeXML($email).'"/>';
				}
			}
		}
		$sql = "select * from part_mailinglist where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return 
			'<mailinglist xmlns="'.$this->_buildnamespace('1.0').'">'.
			'<subscribe>'.
			($subscribe_error!='' ? '<error key="'.$subscribe_error.'"/>' : '').
			$subscribe_body.
			'</subscribe>'.
			'<unsubscribe>'.
			($unsubscribe_error!='' ? '<error key="'.$unsubscribe_error.'"/>' : '').
			$unsubscribe_body.
			'</unsubscribe>'.
			'</mailinglist>';
		} else {
			return '';
		}
	}
	
	function subscribe($name,$address) {
		global $basePath;
		require_once($basePath.'Editor/Classes/Person.php');
		require_once($basePath.'Editor/Classes/Emailaddress.php');
		$person = new Person();
		$person->setFullName($name);
		$person->save();
		$person->publish();

		$email = new EmailAddress();
		$email->setAddress($address);
		$email->setContainingObjectId($person->getId());
		$email->save();
		$email->publish();
		
		$lists = $this->getMailingListIds();
		foreach ($lists as $list) {
			$sql = "insert into person_mailinglist (person_id,mailinglist_id) values (".Database::int($person->getId()).",".Database::int($list).")";
			Database::insert($sql);
		}
	}
	
	function unsubscribe($address) {
		$lists = $this->getMailingListIds();
		
		$sql = "delete from person_mailinglist using person_mailinglist,emailaddress where emailaddress.containing_object_id=person_mailinglist.person_id and emailaddress.address=".Database::text($address)." and (";
		for ($i=0; $i < count($lists); $i++) { 
			if ($i>0) $sql.=' or ';
			$sql.='person_mailinglist.mailinglist_id='.$lists[$i];
		}
		$sql.=")";
		$rows = Database::delete($sql);
		return $rows>0;
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getToolbars() {
		return array(
			'Postliste' =>
				'<checkboxes name="lists" label="Postlister">
				'.GuiUtils::buildObjectItems('mailinglist').'
				</checkboxes>'
			);
	}
}
?>