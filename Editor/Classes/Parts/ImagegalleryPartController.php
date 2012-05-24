<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagegalleryPartController extends PartController
{
	function ImagegalleryPartController() {
		parent::PartController('imagegallery');
	}
	
	function getFromRequest($id) {
		$part = ImagegalleryPart::load($id);
		$part->setHeight(Request::getInt('height',64));
		$part->setImageGroupId(Request::getInt('group'));
		$part->setFramed(Request::getBoolean('framed'));
		$part->setShowTitle(Request::getBoolean('showTitle'));
		$part->setVariant(Request::getString('variant'));
		return $part;
	}
	
	function createPart() {
		$part = new ImagegalleryPart();
		$part->setHeight(100);
		$part->setVariant('floating');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
	    '<input type="hidden" name="group" value="'.$part->getImageGroupId().'"/>'.
	    '<input type="hidden" name="height" value="'.$part->getHeight().'"/>'.
	    '<input type="hidden" name="framed" value="'.StringUtils::toBoolean($part->getFramed()).'"/>'.
	    '<input type="hidden" name="showTitle" value="'.StringUtils::toBoolean($part->getShowTitle()).'"/>'.
	    '<input type="hidden" name="variant" value="'.$part->getVariant().'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/imagegallery/script.js" type="text/javascript" charset="utf-8"></script>'.
		'<div id="part_imagegallery_container">'.$this->render($part,$context).'</div>';
	}
		
	function buildSub($part,$context) {
		$data = '<imagegallery xmlns="'.$this->getNamespace().'">';
		$data.='<display height="'.$part->getHeight().'" variant="'.StringUtils::escapeXML($part->getVariant()).'" framed="'.StringUtils::toBoolean($part->getFramed()).'" show-title="'.StringUtils::toBoolean($part->getShowTitle()).'"/>';
		if ($part->getImageGroupId()) {
			$sql="SELECT object.data from object,imagegroup_image where imagegroup_image.image_id = object.id and imagegroup_image.imagegroup_id=".Database::int($part->getImageGroupId())." order by object.title";
			$result = Database::select($sql);
			while ($row = Database::next($result)) {
				$data.=$row['data'];
			}
			Database::free($result);
		}
		$data.='</imagegallery>';
		return $data;
	}
	
	function getToolbars() {
		return array(
			'Billedgalleri' =>
				'<script source="../../Parts/imagegallery/toolbar.js"/>
				<field label="Billedgruppe">
					<dropdown width="200" name="group">
					'.GuiUtils::buildObjectItems('imagegroup').'
					</dropdown>
				</field>
				<field label="H&#248;jde">
					<number-input name="height"/>
				</field>
				<divider/>
				<field label="Variant">
					<dropdown name="variant">
						<item value="floating" title="Flydende"/>
						<item value="changing" title="Skiftende"/>
					</dropdown>
				</field>
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