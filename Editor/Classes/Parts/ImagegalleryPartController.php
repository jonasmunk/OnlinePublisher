<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagegalleryPartController extends PartController
{
	function ImagegalleryPartController() {
		parent::PartController('imagegallery');
	}
	
	function createPart() {
		$part = new ImagegalleryPart();
		$part->setHeight(100);
		$part->setVariant('floating');
		$part->save();
		return $part;
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