<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagePartController extends PartController
{
	function ImagePartController() {
		parent::PartController('image');
	}
	
	function createPart() {
		$part = new ImagePart();
		$part->setScaleMethod('max');
		$part->setScaleHeight(200);
		$part->setImageId(ImageService::getLatestImageId());
		$part->save();
		return $part;
	}
	
	function getToolbars() {
		return array(
			'Billede' =>
			'<script source="../../Parts/image/toolbar.js"/>
			<icon icon="common/new" title="Tilf&#248;j billede" name="addImage"/>
			<icon icon="common/search" title="V&#230;lg billede" name="chooseImage"/>
			<divider/>
			<segmented label="Placering" name="alignment" allow-null="true">
				<item icon="style/align_left" value="left"/>
				<item icon="style/align_center" value="center"/>
				<item icon="style/align_right" value="right"/>
			</segmented>
			<divider/>
			<grid>
				<row>
					<cell label="Bredde:" width="80" right="5">
						<number adaptive="true" allow-null="true" name="scaleWidth"/>
					</cell>
					<cell label="Procent:" width="80">
						<number adaptive="true" allow-null="true" name="scalePercent"/>
					</cell>
				</row>
				<row>
					<cell label="H&#248;jde:" width="80" right="5">
						<number adaptive="true" allow-null="true" name="scaleHeight"/>
					</cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell label="Text:" width="200" right="5">
						<textfield name="text" label="text"/>
					</cell>
				</row>
				<row>
					<cell right="5" label="Gr&#229;tone:"><checkbox name="greyscale"/></cell>
				</row>
			</grid>'
			,
			'Link' =>
			'<grid>
				<row>
					<cell label="Side:" width="200" right="10">
						<dropdown name="page" adaptive="true">
							'.GuiUtils::buildPageItems().'
						</dropdown>
					</cell>
					<cell label="URL:" width="100" right="10">
						<textfield name="url"/>
					</cell>
					<cell label="Billede:" width="200" right="10">
						<dropdown name="image" adaptive="true">
							'.GuiUtils::buildObjectItems('image').'
						</dropdown>
					</cell>
				</row>
				<row>
					<cell label="Fil:" width="200" right="10">
						<dropdown name="file" adaptive="true">
							'.GuiUtils::buildObjectItems('file').'
						</dropdown>
					</cell>
					<cell label="E-mail:" width="100" right="10">
						<textfield name="email"/>
					</cell>
					<cell label="Samme billede:" right="10">
						<checkbox name="sameimage"/>
					</cell>
				</row>
			</grid>'
		);
	}
}
?>