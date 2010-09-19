<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Imagegallery
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');

class PartImagegallery extends LegacyPartController {
	
	function PartImagegallery($id=0) {
		parent::LegacyPartController('imagegallery');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		global $baseUrl;
		if ($part = ImagegalleryPart::load($this->id)) {
			return
		    '<input type="hidden" name="group" value="'.$part->getImageGroupId().'"/>'.
		    '<input type="hidden" name="height" value="'.$part->getHeight().'"/>'.
		    '<input type="hidden" name="framed" value="'.StringUtils::toBoolean($part->getFramed()).'"/>'.
		    '<input type="hidden" name="showTitle" value="'.StringUtils::toBoolean($part->getShowTitle()).'"/>'.
		    '<input type="hidden" name="variant" value="'.$part->getVariant().'"/>'.
			'<script src="'.$baseUrl.'Editor/Parts/imagegallery/script.js" type="text/javascript" charset="utf-8"></script>'.
			'<div id="part_imagegallery_container">'.$this->render().'</div>';
		}
		return '';
	}
	
	function sub_update() {
		$part = ImagegalleryPart::load($this->id);
		$this->populate($part);
		$part->save();
	}
	
	function populate(&$part) {
		$part->setHeight(Request::getInt('height',64));
		$part->setImageGroupId(Request::getInt('group'));
		$part->setFramed(Request::getBoolean('framed'));
		$part->setShowTitle(Request::getBoolean('showTitle'));
		$part->setVariant(Request::getString('variant'));
	}
	
	function sub_build($context) {
		$part = ImagegalleryPart::load($this->id);
		if ($part) {
			return $this->generate($part);
		}
		return '';
	}
		
	function sub_preview() {
		$part = new ImagegalleryPart();
		$this->populate($part);
		return $this->generate($part);
	}
		
	function generate($part) {
		$data = '<imagegallery xmlns="'.$this->_buildnamespace('1.0').'">';
		$data.='<display height="'.$part->getHeight().'" variant="'.StringUtils::escapeXML($part->getVariant()).'" framed="'.($part->getFramed() ? 'true' : 'false').'" show-title="'.($part->getShowTitle() ? 'true' : 'false').'"/>';
		if ($part->getImageGroupId()) {
			$sql="SELECT object.data from object,imagegroup_image where imagegroup_image.image_id = object.id and imagegroup_image.imagegroup_id=".$part->getImageGroupId()." order by object.title";
			$result = Database::select($sql);
			while ($row = Database::next($result)) {
				$data.=$row['data'];
			}
			Database::free($result);
		}
		$data.='</imagegallery>';
		return $data;
	}
	
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getToolbars() {
		return array(
			'Billedgalleri' =>
			'<script source="../../Parts/imagegallery/toolbar.js"/>
			<dropdown label="Billedgruppe" width="200" name="group">
			'.GuiUtils::buildObjectItems('imagegroup').'
			</dropdown>
			<number label="H&#248;jde" name="height"/>
			<divider/>
			<dropdown label="Variant" name="variant">
				<item value="floating" title="Flydende"/>
				<item value="changing" title="Skiftende"/>
			</dropdown>
			<grid>
				<row>
					<cell right="5"><checkbox name="showTitle"/><label>Vis titel</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="framed"/><label>Indrammet</label></cell>
				</row>
			</grid>'
			);
	}
	
}
?>