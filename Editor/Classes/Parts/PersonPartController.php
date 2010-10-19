<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PersonPartController extends PartController
{
	function PersonPartController() {
		parent::PartController('person');
	}
	
	function createPart() {
		$part = new PersonPart();
		$part->setPersonId(ObjectService::getLatestId('person'));
		$part->setShowFirstName(true);
		$part->setShowMiddleName(true);
		$part->setShowLastName(true);
		$part->setShowInitials(false);
		$part->setShowStreetname(true);
		$part->setShowZipcode(true);
		$part->setShowCity(true);
		$part->setShowCountry(true);
		$part->setShowNickname(false);
		$part->setShowJobTitle(true);
		$part->setShowSex(false);
		$part->setShowEmailJob(true);
		$part->setShowEmailPrivate(true);
		$part->setShowPhoneJob(true);
		$part->setShowPhonePrivate(true);
		$part->setShowWebAddress(true);
		$part->setShowImage(true);
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function getFromRequest($id) {
		$part = PersonPart::load($id);
		$part->setShowFirstName(Request::getBoolean('show_firstname'));
		$part->setShowMiddleName(Request::getBoolean('show_middlename'));
		$part->setShowLastName(Request::getBoolean('show_surname'));
		$part->setShowInitials(Request::getBoolean('show_initials'));
		$part->setShowStreetname(Request::getBoolean('show_streetname'));
		$part->setShowZipcode(Request::getBoolean('show_zipcode'));
		$part->setShowCity(Request::getBoolean('show_city'));
		$part->setShowCountry(Request::getBoolean('show_country'));
		$part->setShowNickname(Request::getBoolean('show_nickname'));
		$part->setShowJobTitle(Request::getBoolean('show_jobtitle'));
		$part->setShowSex(Request::getBoolean('show_sex'));
		$part->setShowEmailJob(Request::getBoolean('show_emailjob'));
		$part->setShowEmailPrivate(Request::getBoolean('show_emailprivate'));
		$part->setShowPhoneJob(Request::getBoolean('show_phonejob'));
		$part->setShowPhonePrivate(Request::getBoolean('show_phoneprivate'));
		$part->setShowWebAddress(Request::getBoolean('show_webaddress'));
		$part->setShowImage(Request::getBoolean('show_image'));
		$part->setPersonId(Request::getInt('personId'));
		$part->setAlign(Request::getString('align'));
		return $part;
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
		'<input type="hidden" name="align" value="'.$part->getAlign().'"/>'.
		'<input type="hidden" name="show_firstname" value="'.$this->_intToBool($part->getShowFirstName()).'"/>'.
		'<input type="hidden" name="show_middlename" value="'.$this->_intToBool($part->getShowMiddleName()).'"/>'.
		'<input type="hidden" name="show_surname" value="'.$this->_intToBool($part->getShowLastName()).'"/>'.
		'<input type="hidden" name="show_initials" value="'.$this->_intToBool($part->getShowInitials()).'"/>'.
		'<input type="hidden" name="show_streetname" value="'.$this->_intToBool($part->getShowStreetname()).'"/>'.
		'<input type="hidden" name="show_zipcode" value="'.$this->_intToBool($part->getShowZipCode()).'"/>'.
		'<input type="hidden" name="show_city" value="'.$this->_intToBool($part->getShowCity()).'"/>'.
		'<input type="hidden" name="show_country" value="'.$this->_intToBool($part->getShowCountry()).'"/>'.
		'<input type="hidden" name="show_nickname" value="'.$this->_intToBool($part->getShowNickname()).'"/>'.
		'<input type="hidden" name="show_jobtitle" value="'.$this->_intToBool($part->getShowJobTitle()).'"/>'.
		'<input type="hidden" name="show_sex" value="'.$this->_intToBool($part->getShowSex()).'"/>'.
		'<input type="hidden" name="show_emailjob" value="'.$this->_intToBool($part->getShowEmailJob()).'"/>'.
		'<input type="hidden" name="show_emailprivate" value="'.$this->_intToBool($part->getShowEmailPrivate()).'"/>'.
		'<input type="hidden" name="show_phonejob" value="'.$this->_intToBool($part->getShowPhoneJob()).'"/>'.
		'<input type="hidden" name="show_phoneprivate" value="'.$this->_intToBool($part->getShowPhonePrivate()).'"/>'.
		'<input type="hidden" name="show_webaddress" value="'.$this->_intToBool($part->getShowWebAddress()).'"/>'.
		'<input type="hidden" name="show_image" value="'.$this->_intToBool($part->getShowImage()).'"/>'.
		'<input type="hidden" name="personId" value="'.$part->getPersonId().'"/>'.
		'<div align="'.$part->getAlign().'">'.
		'<div id="part_person_container">'.StringUtils::fromUnicode($this->render($part,$context)).'</div>'.
		'</div>'.
		'<script src="'.$baseUrl.'Editor/Parts/person/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function _intToBool($val){
		return $val==1 ? "true" : "false";
	}
		
	function buildSub($part,$context) {
		$data='<person xmlns="'.$this->getNamespace().'">';
		if ($personData = Object::getObjectData($part->getPersonId())) {
			$data.= 
			'<display firstname="'.($part->getShowFirstName() ? 'true' : 'false').'"'.
			' middlename="'.($part->getShowMiddleName() ? 'true' : 'false').'"'.
			' surname="'.($part->getShowLastName() ? 'true' : 'false').'"'.
			' initials="'.($part->getShowInitials() ? 'true' : 'false').'"'.
			' nickname="'.($part->getShowNickname() ? 'true' : 'false').'"'.
			' jobtitle="'.($part->getShowJobTitle() ? 'true' : 'false').'"'.
			' sex="'.($part->getShowSex() ? 'true' : 'false').'"'.
			' email_job="'.($part->getShowEmailJob() ? 'true' : 'false').'"'.
			' email_private="'.($part->getShowEmailPrivate() ? 'true' : 'false').'"'.
			' phone_job="'.($part->getShowPhoneJob() ? 'true' : 'false').'"'.
			' phone_private="'.($part->getShowPhonePrivate() ? 'true' : 'false').'"'.
			' streetname="'.($part->getShowStreetname() ? 'true' : 'false').'"'.
			' zipcode="'.($part->getShowZipcode() ? 'true' : 'false').'"'.
			' city="'.($part->getShowCity() ? 'true' : 'false').'"'.
			' country="'.($part->getShowCountry() ? 'true' : 'false').'"'.
			' webaddress="'.($part->getShowWebAddress() ? 'true' : 'false').'"'.
			' image="'.($part->getShowImage() ? 'true' : 'false').'"'.
			'/>';
			if ($part->getAlign()!='') {
				$data.='<style align="'.$part->getAlign().'"/>';
			}
			$data.=$personData;
		}
		$data.='</person>';
		return $data;
	}
	
	function importSub($node,$part) {
		if ($object = DOMUtils::getFirstDescendant($node,'object')) {
			if ($id = intval($object->getAttribute('id'))) {
				$part->setPersonId($id);
			}
		}
		if ($display = DOMUtils::getFirstDescendant($node,'display')) {
			$part->setShowFirstName($display->getAttribute('firstname')=='true');
			$part->setShowMiddleName($display->getAttribute('middlename')=='true');
			$part->setShowLastName($display->getAttribute('surname')=='true');
			$part->setShowInitials($display->getAttribute('initials')=='true');
			$part->setShowStreetname($display->getAttribute('streetname')=='true');
			$part->setShowZipcode($display->getAttribute('zipcode')=='true');
			$part->setShowCity($display->getAttribute('city')=='true');
			$part->setShowCountry($display->getAttribute('country')=='true');
			$part->setShowNickname($display->getAttribute('nickname')=='true');
			$part->setShowJobTitle($display->getAttribute('jobtitle')=='true');
			$part->setShowSex($display->getAttribute('sex')=='true');
			$part->setShowEmailJob($display->getAttribute('email_job')=='true');
			$part->setShowEmailPrivate($display->getAttribute('email_private')=='true');
			$part->setShowPhoneJob($display->getAttribute('phone_job')=='true');
			$part->setShowPhonePrivate($display->getAttribute('phone_private')=='true');
			$part->setShowWebAddress($display->getAttribute('webaddress')=='true');
			$part->setShowImage($display->getAttribute('image')=='true');
		}
		if ($style = DOMUtils::getFirstDescendant($node,'style')) {
			$part->setAlign($style->getAttribute('align'));
		}
	}
	
	function getToolbars() {
		return array('Person' => '
			<script source="../../Parts/person/toolbar.js"/>
			<segmented label="Placering" name="alignment" allow-null="true">
				<item icon="style/align_left" value="left"/>
				<item icon="style/align_center" value="center"/>
				<item icon="style/align_right" value="right"/>
			</segmented>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showFirstName"/><label>Fornavn</label></cell>
					<cell right="5"><checkbox name="showMiddleName"/><label>Mellemnavn</label></cell>
					<cell right="5"><checkbox name="showSurname"/><label>Efternavn</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showInitials"/><label>Initialer</label></cell>
					<cell right="5"><checkbox name="showNickname"/><label>Kaldenavn</label></cell>
					<cell right="5"><checkbox name="showSex"/><label>Koen</label></cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showImage"/><label>Billede</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showWebaddress"/><label>Webadresse</label></cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showStreetname"/><label>Gade</label></cell>
					<cell right="5"><checkbox name="showCity"/><label>By</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showZipcode"/><label>Postnummer</label></cell>
					<cell right="5"><checkbox name="showCountry"/><label>Land</label></cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showEmailPrivate"/><label>Email (privat)</label></cell>
					<cell right="5"><checkbox name="showPhonePrivate"/><label>Telefon (privat)</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showEmailJob"/><label>Email (job)</label></cell>
					<cell right="5"><checkbox name="showPhoneJob"/><label>Telefon (job)</label></cell>
				</row>
			</grid>
		');
	}
}