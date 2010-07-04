<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Part.php');

class PartPerson extends Part {
	
	function PartPerson($id=0) {
		parent::Part('person');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		global $baseUrl;
		$sql = "select * from part_person where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$output=
			'<input type="hidden" name="align" value="'.$row['align'].'"/>'.
			'<input type="hidden" name="show_firstname" value="'.$this->_intToBool($row['show_firstname']).'"/>'.
			'<input type="hidden" name="show_middlename" value="'.$this->_intToBool($row['show_middlename']).'"/>'.
			'<input type="hidden" name="show_surname" value="'.$this->_intToBool($row['show_surname']).'"/>'.
			'<input type="hidden" name="show_initials" value="'.$this->_intToBool($row['show_initials']).'"/>'.
			'<input type="hidden" name="show_streetname" value="'.$this->_intToBool($row['show_streetname']).'"/>'.
			'<input type="hidden" name="show_zipcode" value="'.$this->_intToBool($row['show_zipcode']).'"/>'.
			'<input type="hidden" name="show_city" value="'.$this->_intToBool($row['show_city']).'"/>'.
			'<input type="hidden" name="show_country" value="'.$this->_intToBool($row['show_country']).'"/>'.
			'<input type="hidden" name="show_nickname" value="'.$this->_intToBool($row['show_nickname']).'"/>'.
			'<input type="hidden" name="show_jobtitle" value="'.$this->_intToBool($row['show_jobtitle']).'"/>'.
			'<input type="hidden" name="show_sex" value="'.$this->_intToBool($row['show_sex']).'"/>'.
			'<input type="hidden" name="show_emailjob" value="'.$this->_intToBool($row['show_email_job']).'"/>'.
			'<input type="hidden" name="show_emailprivate" value="'.$this->_intToBool($row['show_email_private']).'"/>'.
			'<input type="hidden" name="show_phonejob" value="'.$this->_intToBool($row['show_phone_job']).'"/>'.
			'<input type="hidden" name="show_phoneprivate" value="'.$this->_intToBool($row['show_phone_private']).'"/>'.
			'<input type="hidden" name="show_webaddress" value="'.$this->_intToBool($row['show_webaddress']).'"/>'.
			'<input type="hidden" name="show_image" value="'.$this->_intToBool($row['show_image']).'"/>'.
			'<input type="hidden" name="personId" value="'.$row['person_id'].'"/>'.
			'<div align="'.$row['align'].'">'.
			'<div id="part_person_container">'.$this->render().'</div>'.
			'</div>'.
			'<script src="'.$baseUrl.'Editor/Parts/person/script.js" type="text/javascript" charset="utf-8"></script>';
			return $output;
		} else {
			return '';
		}
	}
	
	function _intToBool($val){
		return $val==1 ? "true" : "false";
	}

	function sub_create() {
		$sql = "insert into part_person (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_person where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$show_firstname = requestPostBoolean('show_firstname');
		$show_middlename = requestPostBoolean('show_middlename');
		$show_surname = requestPostBoolean('show_surname');
		$show_initials = requestPostBoolean('show_initials');
		$show_streetname = requestPostBoolean('show_streetname');
		$show_zipcode = requestPostBoolean('show_zipcode');
		$show_city = requestPostBoolean('show_city');
		$show_country = requestPostBoolean('show_country');
		$show_nickname = requestPostBoolean('show_nickname');
		$show_jobtitle = requestPostBoolean('show_jobtitle');
		$show_sex = requestPostBoolean('show_sex');
		$show_emailjob = requestPostBoolean('show_emailjob');
		$show_emailprivate = requestPostBoolean('show_emailprivate');
		$show_phonejob = requestPostBoolean('show_phonejob');
		$show_phoneprivate = requestPostBoolean('show_phoneprivate');
		$show_webaddress = requestPostBoolean('show_webaddress');
		$show_image = requestPostBoolean('show_image');
		$person_id = requestPostNumber('personId');
		$align = requestPostText('align');

		$sql="update part_person set".
		" person_id=".sqlInt($person_id).
		" ,align=".Database::text($align).
		" ,show_firstname=".sqlBoolean($show_firstname).
		" ,show_middlename=".sqlBoolean($show_middlename).
		" ,show_surname=".sqlBoolean($show_surname).
		" ,show_initials=".sqlBoolean($show_initials).
		" ,show_streetname=".sqlBoolean($show_streetname).
		" ,show_zipcode=".sqlBoolean($show_zipcode).
		" ,show_city=".sqlBoolean($show_city).
		" ,show_country=".sqlBoolean($show_country).
		" ,show_nickname=".sqlBoolean($show_nickname).
		" ,show_jobtitle=".sqlBoolean($show_jobtitle).
		" ,show_sex=".sqlBoolean($show_sex).
		" ,show_email_job=".sqlBoolean($show_emailjob).
		" ,show_email_private=".sqlBoolean($show_emailprivate).
		" ,show_phone_job=".sqlBoolean($show_phonejob).
		" ,show_phone_private=".sqlBoolean($show_phoneprivate).
		" ,show_webaddress=".sqlBoolean($show_webaddress).
		" ,show_image=".sqlBoolean($show_image).
		" where part_id=".$this->id;
		Database::update($sql);
	}
		
	function sub_build($context) {
		$data='<person xmlns="'.$this->_buildnamespace('1.0').'">';
		$sql = "select part_person.*,object.data from part_person,object where part_person.person_id = object.id and part_person.part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data.= 
			'<display firstname="'.($row['show_firstname'] ? 'true' : 'false').'"'.
			' middlename="'.($row['show_middlename'] ? 'true' : 'false').'"'.
			' surname="'.($row['show_surname'] ? 'true' : 'false').'"'.
			' initials="'.($row['show_initials'] ? 'true' : 'false').'"'.
			' nickname="'.($row['show_nickname'] ? 'true' : 'false').'"'.
			' jobtitle="'.($row['show_jobtitle'] ? 'true' : 'false').'"'.
			' sex="'.($row['show_sex'] ? 'true' : 'false').'"'.
			' email_job="'.($row['show_email_job'] ? 'true' : 'false').'"'.
			' email_private="'.($row['show_email_private'] ? 'true' : 'false').'"'.
			' phone_job="'.($row['show_phone_job'] ? 'true' : 'false').'"'.
			' phone_private="'.($row['show_phone_private'] ? 'true' : 'false').'"'.
			' streetname="'.($row['show_streetname'] ? 'true' : 'false').'"'.
			' zipcode="'.($row['show_zipcode'] ? 'true' : 'false').'"'.
			' city="'.($row['show_city'] ? 'true' : 'false').'"'.
			' country="'.($row['show_country'] ? 'true' : 'false').'"'.
			' webaddress="'.($row['show_webaddress'] ? 'true' : 'false').'"'.
			' image="'.($row['show_image'] ? 'true' : 'false').'"'.
			'/>';
			if ($row['align']!='') {
				$data.='<style align="'.$row['align'].'"/>';
			}
			$data.=$row['data'];
		}
		$data.='</person>';
		return $data;
	}
		
	function sub_preview() {
		$data='<person xmlns="'.$this->_buildnamespace('1.0').'">';
		$sql = "select object.data from object where object.id=".Request::getInt('personId');
		if ($row = Database::selectFirst($sql)) {
			$data.= 
			'<display'.
			' firstname="'.Request::getString('show_firstname').'"'.
			' middlename="'.Request::getString('show_middlename').'"'.
			' surname="'.Request::getString('show_surname').'"'.
			' initials="'.Request::getString('show_initials').'"'.
			' nickname="'.Request::getString('show_nickname').'"'.
			' jobtitle="'.Request::getString('show_jobtitle').'"'.
			' sex="'.Request::getString('show_sex').'"'.
			' email_job="'.Request::getString('show_emailjob').'"'.
			' email_private="'.Request::getString('show_emailprivate').'"'.
			' phone_job="'.Request::getString('show_phonejob').'"'.
			' phone_private="'.Request::getString('show_phoneprivate').'"'.
			' streetname="'.Request::getString('show_streetname').'"'.
			' zipcode="'.Request::getString('show_zipcode').'"'.
			' city="'.Request::getString('show_city').'"'.
			' country="'.Request::getString('show_country').'"'.
			' webaddress="'.Request::getString('show_webaddress').'"'.
			' image="'.Request::getString('show_image').'"'.
			'/>';
			if (Request::getString('align')!='') {
				$data.='<style align="'.Request::getString('align').'"/>';
			}
			$data.=$row['data'];
		}
		$data.='</person>';
		return $data;
	}
	
	function sub_import(&$node) {
		$object =& $node->selectNodes('object',1);
		$sql = "update part_person set".
		" person_id=".sqlInt($object->getAttribute('id')).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getMainToolbarBody() {
		return '
			<script source="../../Parts/person/toolbar.js"/>
			<divider/>
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
		';
	}
}
?>