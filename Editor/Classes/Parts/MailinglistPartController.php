<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class MailinglistPartController extends PartController
{
	function MailinglistPartController() {
		parent::PartController('mailinglist');
	}
	
	function getFromRequest($id) {
		$part = MailinglistPart::load($id);
		$lists = explode(',',Request::getString('mailinglists'));
		$part->setMailinglistIds($lists);
		return $part;
	}
	
	function editor($part,$context) {
		$ids = $part->getMailinglistIds();
		return
		$this->render($part,$context).
		'<input type="hidden" name="mailinglists" value="'.implode(',',$ids).'"/>';
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
		
	function createPart() {
		$part = new MailinglistPart();
		$part->save();
		return $part;
	}
	
	function isDynamic($part) {
		return true;
	}
	
	function buildSub($part,$context) {
		$action = Request::getString($part->getId().'_action');
		$name = Request::getString($part->getId().'_name');
		$email = Request::getString($part->getId().'_email');
		$subscribe_error = '';
		$subscribe_body = '';
		$unsubscribe_error = '';
		$unsubscribe_body = '';
		if ($action=='subscribe') {
			if ($name=='') {
				$subscribe_error = 'noname';
			} else if ($email=='') {
				$subscribe_error = 'noemail';
			} else if (!ValidateUtils::validateEmail($email)) {
				$subscribe_error = 'invalidemail';
			}
			if ($subscribe_error!='') {
				$subscribe_body .= '<value key="name" value="'.StringUtils::escapeXML($name).'"/>';
				$subscribe_body .= '<value key="email" value="'.StringUtils::escapeXML($email).'"/>';
			} else {
				$this->subscribe($part,$name,$email);
				$subscribe_body .= '<success/>';
			}
		} else if ($action=='unsubscribe') {
			if ($email=='') {
				$unsubscribe_error = 'noemail';
				$unsubscribe_body .= '<value key="email" value="'.StringUtils::escapeXML($email).'"/>';
			} else if (!ValidateUtils::validateEmail($email)) {
				$unsubscribe_error = 'invalidemail';
				$unsubscribe_body .= '<value key="email" value="'.StringUtils::escapeXML($email).'"/>';
			} else {
				if ($this->unsubscribe($part,$email)) {
					$unsubscribe_body .= '<success/>';
				} else {
					$unsubscribe_error = 'notsubscribed';
					$unsubscribe_body .= '<value key="email" value="'.StringUtils::escapeXML($email).'"/>';
				}
			}
		}
		$ids = $part->getMailinglistIds();
		$xml = 
		'<mailinglist xmlns="'.$this->getNamespace().'">'.
		'<lists>';
		foreach ($ids as $id) {
			$xml.='<id>'.$id.'</id>';
		}
		$xml.='</lists>'.
		'<subscribe>'.
		($subscribe_error!='' ? '<error key="'.$subscribe_error.'"/>' : '').
		$subscribe_body.
		'</subscribe>'.
		'<unsubscribe>'.
		($unsubscribe_error!='' ? '<error key="'.$unsubscribe_error.'"/>' : '').
		$unsubscribe_body.
		'</unsubscribe>'.
		'</mailinglist>';
		return $xml;
	}
	
	function importSub($node,$part) {
		$mailinglistIds = array();
		if ($lists = DOMUtils::getFirstDescendant($node,'lists')) {
			$ids = DOMUtils::getChildElements($lists,'id');
			foreach ($ids as $idNode) {
				$str = DOMUtils::getText($idNode);
				$mailinglistIds[] = intval($str);
			}
		}
		$part->setMailinglistIds($mailinglistIds);
	}
	
	function subscribe($part,$name,$address) {
		$person = new Person();
		$person->setFullName($name);
		$person->save();
		$person->publish();

		$email = new Emailaddress();
		$email->setAddress($address);
		$email->setContainingObjectId($person->getId());
		$email->save();
		$email->publish();
		
		$lists = $part->getMailinglistIds();
		foreach ($lists as $list) {
			$sql = "insert into person_mailinglist (person_id,mailinglist_id) values (".Database::int($person->getId()).",".Database::int($list).")";
			Database::insert($sql);
		}
	}
	
	function unsubscribe($part,$address) {
		$lists = $part->getMailingListIds();
		
		$sql = "delete from person_mailinglist using person_mailinglist,emailaddress where emailaddress.containing_object_id=person_mailinglist.person_id and emailaddress.address=".Database::text($address)." and (";
		for ($i=0; $i < count($lists); $i++) { 
			if ($i>0) $sql.=' or ';
			$sql.='person_mailinglist.mailinglist_id='.$lists[$i];
		}
		$sql.=")";
		$rows = Database::delete($sql);
		return $rows>0;
	}

	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Mailing list','da'=>'Postliste')) => '
				<field label="{Mailing lists; da:Postlister}">
					<checkboxes name="lists">
					'.GuiUtils::buildObjectItems('mailinglist').'
					</checkboxes>
				</field>
			'
		);
	}
}