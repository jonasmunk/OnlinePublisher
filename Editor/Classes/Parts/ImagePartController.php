<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ImagePartController extends PartController
{
	function ImagePartController() {
		parent::PartController('image');
	}
		
	function setLatestUploadId($id) {
		$_SESSION['part.image.latest_upload_id'] = $id;
	}
	
	function getLatestUploadId() {
		return $_SESSION['part.image.latest_upload_id'];
	}
	
	static function createPart() {
		$part = new ImagePart();
		$part->setScaleMethod('max');
		$part->setScaleHeight(200);
		$part->setImageId(ImageService::getLatestImageId());
		$part->save();
		return $part;
	}
	
	function getFromRequest($id) {
		$part = ImagePart::load($id);
		$part->setImageId(Request::getInt('imageId'));
		$part->setText(Request::getString('text'));
		$part->setAlign(Request::getString('align'));
		$part->setScaleMethod(Request::getString('scalemethod'));
		$part->setScalePercent(Request::getInt('scalepercent'));
		$part->setScaleWidth(Request::getInt('scalewidth'));
		$part->setScaleHeight(Request::getInt('scaleheight'));
		$part->setGreyscale(Request::getBoolean('greyscale'));
		$part->setAdaptive(Request::getBoolean('adaptive'));
		$part->setFrame(Request::getString('frame'));
		return $part;
	}
	
	function updateAdditional($part) {
		$linkType = Request::getString('linkType');
		$linkValue = Request::getString('linkValue');
		if ($linkType=='sameimage') {
		    $linkValue='';
		}
		$sql = "select * from part_link where source_type='entireimage' and part_id=".Database::int($part->getId());
		if ($row=Database::selectFirst($sql)) {
		    if ($linkType!="") {
		        $sql = "update part_link set target_type=".Database::text($linkType).
		        ",target_value=".Database::text($linkValue)." where source_type='entireimage'".
		        " and part_id=".Database::int($part->getId());
		        Database::update($sql);
		    } else {
		        $sql="delete from part_link where source_type='entireimage' and part_id=".Database::int($part->getId());
		        Database::delete($sql);
		    }
		} elseif ($linkType!="") {
	        $sql = "insert into part_link (target_type,target_value,source_type,part_id)".
	        " values (".Database::text($linkType).",".Database::text($linkValue).",'entireimage',".Database::int($part->getId()).")";
	        Database::insert($sql);
		}
	}
	
	function editor($part,$context) {
		$link = $this->getSingleLink($part,'entireimage');
		if (!$link) {
			$link = array('target_type'=>'','target_value'=>'');
		}
		return $this->buildHiddenFields(array(
			'imageId' => $part->getImageId(),
			'align' => $part->getAlign(),
			'greyscale' => $part->getGreyscale() ? 'true' : 'false',
			'adaptive' => $part->getAdaptive() ? 'true' : 'false',
			'linkType' => $link['target_type'],
			'linkValue' => $link['target_value'],
			'scalemethod' => $part->getScaleMethod(),
			'scalepercent' => $part->getScalePercent()>0 ? $part->getScalePercent() : '',
			'scalewidth' => $part->getScaleWidth()>0 ? $part->getScaleWidth() : '',
			'scaleheight' => $part->getScaleHeight()>0 ? $part->getScaleHeight() : '',
			'text' => $part->getText(),
			'frame' => $part->getFrame()
		)).
		'<div id="part_image_container">'.$this->render($part,$context).'</div>'.
		'<script src="'.ConfigurationService::getBaseUrl().'hui/ext/ImagePaster.js" type="text/javascript" charset="utf-8"></script>'.
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/image/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function buildSub($part,$context) {
		$xml = '<image xmlns="'.$this->getNamespace().'">';
		$xml.='<style';
		if ($part->getAlign()!='') {
			$xml.=' align="'.Strings::escapeXML($part->getAlign()).'"';
		}
		if ($part->getFrame()!='') {
			$xml.=' frame="'.Strings::escapeXML($part->getFrame()).'"';
		}
        $xml.=' adaptive="'.($part->getAdaptive() ? 'true' : 'false').'"';
        $xml.='/>';
        if ($part->getImageId()>0) {
			$sql="select object.data,image.* from object,image where image.object_id = object.id and object.id=".Database::int($part->getImageId());
			if ($image = Database::selectFirst($sql)) {
				$xml.=$this->buildTransformTag($image,$part);
				if ($link = $this->getSingleLink($part,'entireimage')) {
				    $xml.=$this->_buildLinkTag($link,$part->getImageId());
				}
				$xml.=$image['data'];
			} else {
				$xml.='<transform scale-method="'.$part->getScaleMethod().'" scale-width="'.$part->getScaleWidth().'" scale-height="'.$part->getScaleHeight().'"/>';
				Log::debug('Unable to load image with id='.$part->getImageId());
			}
		}
		if (Strings::isNotBlank($part->getText())) {
			$xml.='<text>'.Strings::escapeEncodedXML($part->getText()).'</text>';
		}
		$xml.='</image>';
		return $xml;
	}
	
	function _buildLinkTag($link,$imageId) {
	    $atts = '';
	    if ($link['target_type']=='url') {
			$atts.=' url="'.Strings::escapeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='page') {
			$atts.=' page="'.Strings::escapeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='email') {
			$atts.=' email="'.Strings::escapeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='file') {
			$atts.=' file="'.Strings::escapeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='image') {
			$image = Image::load($link['target_value']);
			if ($image) {
				$atts.=' image="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'" note="'.Strings::escapeXML($image->getNote()).'"';
			}
		}
		else if ($link['target_type']=='sameimage') {
			$image = Image::load($imageId);
			if ($image) {
				$atts.=' image="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'" note="'.Strings::escapeXML($image->getNote()).'"';
			}
		}
	    if ($link['path']!='') {
			$atts.=' path="'.Strings::escapeXML($link['path']).'"';
		}
	    return '<link'.$atts.'/>';
	}
	
	/**
	 * Generates the appropriate transformation tag
	 * @param array $image The image database row
	 * @param array $part The part database row
	 * @return string The XMl tag, empty string if no transformation
	 * @private
	 */
	function buildTransformTag($image,$part) {
		$tag='';
		if ($part->getGreyscale() || $part->getScaleMethod()) {
			$dims = $this->calculateComputedDimensions($part,$image);
			$tag.='<transform display-width="'.$dims['width'].'" display-height="'.$dims['height'].'"';
			$tag.=' scale-method="'.$part->getScaleMethod().'" scale-width="'.$part->getScaleWidth().'" scale-height="'.$part->getScaleHeight().'"';
			if ($part->getGreyscale()) {
				$tag.=' greyscale="true"';
			}
			if ($part->getScaleMethod()=='percent' && $part->getScalePercent()) {
				$tag.=' scale-percent="'.$part->getScalePercent().'"';
			}
			elseif ($part->getScaleMethod()=='exact') {
				if ($part->getScaleWidth()) {
					$tag.=' width="'.$part->getScaleWidth().'"';
				}
				if ($part->getScaleHeight()) {
					$tag.=' height="'.$part->getScaleHeight().'"';
				}
			}
			elseif ($part->getScaleMethod()=='max') {
				if ($part->getScaleWidth()) {
					$tag.=' max-width="'.$part->getScaleWidth().'"';
				}
				if ($part->getScaleHeight()) {
					$tag.=' max-height="'.$part->getScaleHeight().'"';
				}
			}
			$tag.='/>';
		}
		return $tag;
	}
	
	function calculateComputedDimensions($part,$image) {
		$width = $image['width'];
		$height = $image['height'];
		if ($part->getScaleMethod()=='percent') {
			$width=round($part->getScalePercent()*$image['width']/100);
			$height=round($part->getScalePercent()*$image['height']/100);
		} elseif ($part->getScaleMethod()=='max') {
			// If only height is set
			if ($part->getScaleHeight() && !$part->getScaleWidth()) {
				$height=$part->getScaleHeight();
				$width=($part->getScaleHeight()/$image['height'])*$width;
			}
			// If only width is set
			else if ($part->getScaleWidth() && !$part->getScaleHeight()) {
				$width=$part->getScaleWidth();
				$height=($part->getScaleWidth()/$image['width'])*$height;
			}
			// If both are set
			else if ($part->getScaleWidth() && $part->getScaleHeight()) {
				if ($part->getScaleHeight()/$part->getScaleWidth() > $image['height']/$image['width']) {
					$width=$part->getScaleHeight();
					$height=($part->getScaleWidth()/$image['width'])*$image['height'];
				} else {
					$height=$part->getScaleHeight();
					$width=($part->getScaleHeight()/$image['height'])*$image['width'];
				}
			}
		} elseif ($part->getScaleMethod()=='exact') {
			// If only width is set
			if (!$part->getScaleHeight() && $part->getScaleWidth()) {
				$width=$part->getScaleWidth();
				$height=($part->getScaleWidth()/$image['width'])*$image['height'];
			}
			// if only height is set
			elseif ($part->getScaleHeight() && !$part->getScaleWidth()) {
				$width=($part->getScaleHeight()/$image['height'])*$image['width'];
				$height=$part->getScaleHeight();
			}
			// if both are set
			elseif ($part->getScaleHeight() && $part->getScaleWidth()) {
				$width=$part->getScaleWidth();
				$height=$part->getScaleHeight();
			}
		}
		return array('width' => $width, 'height' => $height);
	}
	
	function importSub($node,$part) {
		$image = $object = DOMUtils::getFirstDescendant($node,'image');
		if (!$image) {
			Log::debug('ImagePartController: No image tag to import');
			return;
		}
		if ($object = DOMUtils::getFirstDescendant($node,'object')) {
			if ($imageId = intval($object->getAttribute('id'))) {
				$part->setImageId($imageId);
			}
		}
		if ($style = DOMUtils::getFirstDescendant($node,'style')) {
			$part->setAdaptive($style->getAttribute('adaptive')==='true');
			$part->setFrame($style->getAttribute('frame'));
        }
		if ($transform = DOMUtils::getFirstDescendant($node,'transform')) {
			$part->setGreyscale($transform->getAttribute('greyscale')==='true');
			$part->setScaleMethod($transform->getAttribute('scale-method'));
			$part->setScaleWidth(intval($transform->getAttribute('scale-width')));
			$part->setScaleHeight(intval($transform->getAttribute('scale-height')));
			$part->setScalePercent(intval($transform->getAttribute('scale-percent')));
		}
		if ($transform = DOMUtils::getFirstDescendant($node,'style')) {
			$part->setAlign($transform->getAttribute('align'));
		}
		if ($text = DOMUtils::getFirstChildElement($image,'text')) {
			$part->setText(DOMUtils::getText($text));
		}
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="{Add image; da:Tilføj billede}" name="imageUploadWindow" width="300">
			<tabs small="true" centered="true">
				<tab title="{Upload; da:Overførsel}" padding="10">
					<upload name="imageUpload" url="../../Parts/image/Upload.php" widget="upload">
						<placeholder title="{Select an image on you computer...; da:Vælg et billede på din computer...}" text="{The image format can be JPEG, PNG or GIF. The file size can at most be; da: Billedets format skal være JPEG, PNG eller GIF. Filens størrelse må højest være} '.GuiUtils::bytesToString(FileSystemService::getMaxUploadSize()).'."/>
					</upload>
					<buttons align="center" top="10">
						<button name="cancelUpload" title="{Close; da:Luk}"/>
						<button name="upload" title="{Select image...; da:Vælg billede...}" highlighted="true"/>
					</buttons>
				</tab>
				<tab title="{Fetch from the net; da:Hent fra nettet}" padding="10">
					<formula name="urlForm">
						<fields labels="above">
							<field label="{Address; da:Adresse}:">
								<text-input key="url"/>
							</field>
						</fields>
					</formula>
					<buttons align="center">
						<button name="cancelFetch" title="{Close; da:Luk}"/>
						<button name="createFromUrl" submit="true" title="{Fetch; da:Hent}" highlighted="true"/>
					</buttons>
				</tab>
			</tabs>			
		</window>
		
		<window title="{Advanced; da:Avanceret}" name="imageAdvancedWindow" width="300" padding="5">
			<formula name="imageAdvancedFormula">
				<fields>
					<field label="{Text; da:Tekst}:">
						<text-input multiline="true" key="text"/>
					</field>
					<field label="{Effects; da:Effekter}:">
						<checkbox key="greyscale" label="{Grayscale; da:Gråtone}"/>
						<checkbox key="adaptive" label="{Adaptive; da:Tilpasset}"/>
					</field>
					<field label="{Frame; da:Ramme}:">
						<dropdown key="frame">
							'.DesignService::getFrameOptions().'
						</dropdown>
					</field>
					<buttons>
						<button name="pasteImage" text="{Insert from clipboard; da:Indsæt fra udklipsholder}"/>
					</buttons>
				</fields>
			</formula>
		</window>
		
		<source name="gallerySource" url="../../Services/ImageChooser/GallerySource.php">
			<parameter key="text" value="@search.value"/>
			<parameter key="subset" value="@imageChooserSelection.value"/>
			<parameter key="group" value="@imageGroupSelection.value"/>
		</source>
		<source name="groupOptionsSource" url="../../Services/Model/Items.php?type=imagegroup"/>
		
		<window title="{Select image; da:Vælg billede}" name="imageChooser" width="700" icon="common/search">
			<layout>
				<middle>
					<left>
						<overflow height="400">
							<selection value="all" name="imageChooserSelection">
								<item text="{All images; da:Alle billeder}" icon="common/image" value="all"/>
								<item text="{Latest; da:Seneste}" icon="common/time" value="latest"/>
								<item text="{Unused; da:Ikke brugt}" icon="monochrome/round_question" value="unused"/>
								<title>{Groups; da:Grupper}</title>
								<item text="{No groups; da:Uden gruppe}" icon="common/folder_grey" value="nogroup"/>
								<items source="groupOptionsSource" name="imageGroupSelection"/>
							</selection>
						</overflow>
					</left>
					<center>
						<bar variant="layout">
							<!--
							<segmented>
								<item icon="view/list" value="list"/>
								<item icon="view/gallery" value="gallery"/>
							</segmented>
							-->
							<button small="true" text="{Add image; da:Tilføj billede}" click="imageUploadWindow.show()"/>
							<right>
								<searchfield expanded-width="200" name="search"/>
							</right>
						</bar>
						<overflow height="375">
							<gallery source="gallerySource" name="imageGallery"/>
						</overflow>
					</center>
				</middle>
			</layout>
		</window>
		';
		return In2iGui::renderFragment($gui);
	}
	
	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Image','da'=>'Billede')) =>
			'<script source="../../Parts/image/toolbar.js"/>
			<icon icon="common/new" title="{Add image; da:Tilføj billede}" name="addImage"/>
			<icon icon="common/search" title="{Select image; da:Vælg billede}" name="chooseImage"/>
			<divider/>
			<field label="{Alignment; da:Placering}">
				<segmented name="alignment" allow-null="true">
					<item icon="style/align_left" value="left"/>
					<item icon="style/align_center" value="center"/>
					<item icon="style/align_right" value="right"/>
				</segmented>
			</field>
			<divider/>
			<grid>
				<row>
					<cell label="{Width; da:Bredde}:" width="80" right="5">
						<number-input adaptive="true" allow-null="true" name="scaleWidth"/>
					</cell>
					<cell label="{Percent; da:Procent}:" width="80">
						<number-input adaptive="true" allow-null="true" name="scalePercent"/>
					</cell>
				</row>
				<row>
					<cell label="{Height; da:Højde}:" width="80" right="5">
						<number-input adaptive="true" allow-null="true" name="scaleHeight"/>
					</cell>
				</row>
			</grid>
			<divider/>
			<!--grid>
				<row>
					<cell label="Text:" width="200" right="5">
						<text-input name="text" label="text"/>
					</cell>
				</row>
				<row>
					<cell right="5" label="Gr&#229;tone:"><checkbox name="greyscale"/></cell>
				</row>
			</grid>
			<divider/-->
			<icon icon="common/settings" title="Avanceret" name="showAdvanced"/>
			'
			,
			'Link' =>
			'<grid>
				<row>
					<cell label="{Page; da:Side}:" width="200" right="10">
						<dropdown name="page" adaptive="true">
							'.GuiUtils::buildPageItems().'
						</dropdown>
					</cell>
					<cell label="{Address; da:Adresse}:" width="100" right="10">
						<text-input name="url"/>
					</cell>
					<cell label="{Image; da:Billede}:" width="200" right="10">
						<dropdown name="image" adaptive="true">
							'.GuiUtils::buildObjectItems('image').'
						</dropdown>
					</cell>
				</row>
				<row>
					<cell label="{File; da:Fil}:" width="200" right="10">
						<dropdown name="file" adaptive="true">
							'.GuiUtils::buildObjectItems('file').'
						</dropdown>
					</cell>
					<cell label="{E-mail; da:E-post}:" width="100" right="10">
						<text-input name="email"/>
					</cell>
					<cell label="{Same image; da:Samme billede}:" right="10">
						<checkbox name="sameimage"/>
					</cell>
				</row>
			</grid>'
		);
	}
}
?>