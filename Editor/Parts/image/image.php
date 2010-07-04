<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once($basePath.'Editor/Classes/Part.php');
require_once($basePath.'Editor/Classes/Image.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');
require_once($basePath.'Editor/Classes/Log.php');

class PartImage extends Part {
	
	function PartImage($id=0) {
		parent::Part('image');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function buildImageUrl($row) {
		$url = '../../../util/images/?id='.$row['image_id'];
		if ($row['greyscale']) {
			$url.='&amp;greyscale=true';
		}
		if ($row['scalemethod']=='percent') {
			$url.='&amp;percent='.$row['scalepercent'];
		}
		elseif ($row['scalemethod']=='max') {
			if ($row['scalewidth']) {
				$url.='&amp;maxwidth='.$row['scalewidth'];
			}
			if ($row['scaleheight']) {
				$url.='&amp;maxheight='.$row['scaleheight'];
			}
		}
		elseif ($row['scalemethod']=='exact') {
			if ($row['scalewidth']) {
				$url.='&amp;width='.$row['scalewidth'];
			}
			if ($row['scaleheight']) {
				$url.='&amp;height='.$row['scaleheight'];
			}
		}
		return $url;
	}
	
	function calculateComputedDimensions($part,$image) {
		$width = $image['width'];
		$height = $image['height'];
		if ($part['scalemethod']=='percent') {
			$width=round($part['scalepercent']*$image['width']/100);
			$height=round($part['scalepercent']*$image['height']/100);
		} elseif ($part['scalemethod']=='max') {
			// If only height is set
			if ($part['scaleheight'] && !$part['scalewidth']) {
				$height=$part['scaleheight'];
				$width=($part['scaleheight']/$image['height'])*$width;
			}
			// If only width is set
			else if ($part['scalewidth'] && !$part['scaleheight']) {
				$width=$part['scalewidth'];
				$height=($part['scalewidth']/$image['width'])*$height;
			}
			// If both are set
			else if ($part['scalewidth'] && $part['scaleheight']) {
				if ($part['scaleheight']/$part['scalewidth'] > $image['height']/$image['width']) {
					$width=$part['scaleheight'];
					$height=($part['scalewidth']/$image['width'])*$image['height'];
				} else {
					$height=$part['scaleheight'];
					$width=($part['scaleheight']/$image['height'])*$image['width'];
				}
			}
		} elseif ($part['scalemethod']=='exact') {
			// If only width is set
			if (!$part['scaleheight'] && $part['scalewidth']) {
				$width=$part['scalewidth'];
				$height=($part['scalewidth']/$image['width'])*$image['height'];
			}
			// if only height is set
			elseif ($part['scaleheight'] && !$part['scalewidth']) {
				$width=($part['scaleheight']/$image['height'])*$image['width'];
				$height=$part['scaleheight'];
			}
			// if both are set
			elseif ($part['scaleheight'] && $part['scalewidth']) {
				$width=$part['scalewidth'];
				$height=$part['scaleheight'];
			}
		}
		return array('width' => $width, 'height' => $height);
	}
	
	function buildHiddenFields($items) {
		$str = '';
		foreach ($items as $key => $value) {
			$str.='<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
		}
		return $str;
	}
	
	function sub_editor($context) {
		global $baseUrl;
		$sql = "select * from part_image where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$link = $this->getSingleLink('entireimage');
			if (!$link) {
				$link = array('target_type'=>'','target_value'=>'');
			}
			return $this->buildHiddenFields(array(
				'imageId' => $row['image_id'],
				'align' => $row['align'],
				'greyscale' => $row['greyscale'] ? 'true' : 'false',
				'linkType' => $link['target_type'],
				'linkValue' => $link['target_value'],
				'scalemethod' => $row['scalemethod'],
				'scalepercent' => $row['scalepercent']>0 ? $row['scalepercent'] : '',
				'scalewidth' => $row['scalewidth']>0 ? $row['scalewidth'] : '',
				'scaleheight' => $row['scaleheight']>0 ? $row['scaleheight'] : '',
				'text' => $row['text']
			)).
			'<div id="part_image_container">'.$this->render().'</div>'.
			'<script src="'.$baseUrl.'Editor/Parts/image/script.js" type="text/javascript" charset="utf-8"></script>';
		}
		return '';
	}
	
	function sub_create() {
		$sql = "insert into part_image (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_image where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$imageId = requestPostNumber('imageId');
		$align = requestPostText('align');
		$scalemethod = requestPostText('scalemethod');
		$scalepercent = requestPostText('scalepercent');
		$scalewidth = requestPostText('scalewidth');
		$scaleheight = requestPostText('scaleheight');
		$greyscale = requestPostText('greyscale')=='true';
		$linkType = requestPostText('linkType');
		$linkValue = requestPostText('linkValue');
		$text = requestPostText('text');
		if ($linkType=='sameimage') {
		    $linkValue='';
		}
		$sql = "update part_image set".
		" image_id=".$imageId.
		",align=".Database::text($align).
		",greyscale=".Database::boolean($greyscale).
		",scalemethod=".Database::text($scalemethod).
		",scalepercent=".Database::text($scalepercent).
		",scalewidth=".Database::text($scalewidth).
		",scaleheight=".Database::text($scaleheight).
		",`text`=".Database::text($text).
		" where part_id=".$this->id;
		Database::update($sql);
		$sql = "select * from part_link where source_type='entireimage' and part_id=".$this->id;
		if ($row=Database::selectFirst($sql)) {
		    if ($linkType!="") {
		        $sql = "update part_link set target_type=".Database::text($linkType).
		        ",target_value=".Database::text($linkValue)." where source_type='entireimage'".
		        " and part_id=".$this->id;
		        Database::update($sql);
		    } else {
		        $sql="delete from part_link where source_type='entireimage' and part_id=".$this->id;
		        Database::delete($sql);
		    }
		} elseif ($linkType!="") {
	        $sql = "insert into part_link (target_type,target_value,source_type,part_id)".
	        " values (".Database::text($linkType).",".Database::text($linkValue).",'entireimage',".$this->id.")";
	        Database::insert($sql);
		}
	}
	
	function sub_import(&$node) {
		$object =& $node->selectNodes('object',1);
		$transform =& $node->selectNodes('transform',1);
		$style =& $node->selectNodes('style',1);
		
		if ($transform!=null) {
			$percent = $transform->getAttribute('scale-percent');
			$maxWidth = $transform->getAttribute('max-width');
			$maxHeight = $transform->getAttribute('max-height');
			$width = $transform->getAttribute('width');
			$height = $transform->getAttribute('height');
			$greyscale = $transform->getAttribute('greyscale');
		} else {
			$percent = null;
			$maxWidth = null;
			$maxHeight = null;
			$width = null;
			$height = null;
			$greyscale = null;
		}
		if ($style!=null) {
			$align = $style->getAttribute('align');
		} else {
			$align = '';
		}
		
		$scaleMethod = '';
		$scaleWidth = 0;
		$scaleHeight = 0;
		$scalePercent = 0;
		
		if ($percent!=null) {
			$scaleMethod = 'percent';
			$scalePercent = $percent;
		} elseif ($maxWidth!=null || $maxHeight!=null) {
			$scaleMethod = 'max';
			$scaleWidth = $maxWidth;
			$scaleHeight = $maxHeight;
		} elseif ($width!=null || $height!=null) {
			$scaleMethod = 'exact';
			$scaleWidth = $width;
			$scaleHeight = $height;
		}
		$sql = "update part_image set".
		" image_id=".Database::int($object->getAttribute('id')).
		",align=".Database::text($align).
		",greyscale=".Database::boolean($greyscale=='true').
		",scalemethod=".Database::text($scaleMethod).
		",scalepercent=".Database::text($scalePercent).
		",scalewidth=".Database::text($scaleWidth).
		",scaleheight=".Database::text($scaleHeight).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_build($context) {
		$sql = "select * from part_image where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$xml = '<image xmlns="'.$this->_buildnamespace('1.0').'">';
            if ($row['align']!='') {
                $xml.='<style align="'.$row['align'].'"/>';
            }
			$sql="select object.data,image.* from object,image where image.object_id = object.id and object.id=".$row['image_id'];
			if ($image = Database::selectFirst($sql)) {
				$xml.=$this->buildTransformTag($image,$row);
				if ($link = $this->getSingleLink('entireimage')) {
				    $xml.=$this->_buildLinkTag($link,$row['image_id']);
				}
				$xml.=$image['data'];
			}
			if ($row['text']!='') {
				$xml.='<text>'.encodeXML($row['text']).'</text>';
			}
			$xml.='</image>';
			return $xml;
		} else {
			return '';
		}
	}
		
	function sub_preview() {
		$params = array(
			'image_id'=>Request::getInt('imageId'),
			'align'=>Request::getString('align'),
			'greyscale'=>Request::getBoolean('greyscale'),
			'scalewidth'=>Request::getInt('scalewidth'),
			'scaleheight'=>Request::getInt('scaleheight'),
			'scalepercent'=>Request::getInt('scalepercent'),
			'scalemethod'=>Request::getString('scalemethod'),
			'text' => Request::getUnicodeString('text')
		);
		return $this->buildXML($params);
	}
	
	function buildXML($params) {
		Log::debug($params);
		$xml = '<image xmlns="'.$this->_buildnamespace('1.0').'">';
           if ($params['align']) {
               $xml.='<style align="'.$params['align'].'"/>';
           }
		$sql="select object.data,image.* from object,image where image.object_id = object.id and object.id=".$params['image_id'];
		if ($image = Database::selectFirst($sql)) {
			$xml.=$this->buildTransformTag($image,$params);
			//if ($link = $this->getSingleLink('entireimage')) {
			//    $xml.=$this->_buildLinkTag($link,$row['image_id']);
			//}
			$xml.=$image['data'];
		}
		if ($params['text']) {
			$xml.='<text>'.encodeXML($params['text']).'</text>';
		}
		$xml.='</image>';
		return $xml;
	}
	
	function _buildLinkTag($link,$imageId) {
	    $atts = '';
	    if ($link['target_type']=='url') {
			$atts.=' url="'.encodeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='page') {
			$atts.=' page="'.encodeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='email') {
			$atts.=' email="'.encodeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='file') {
			$atts.=' file="'.encodeXML($link['target_value']).'"';
		}
		else if ($link['target_type']=='image') {
			$image = Image::load($link['target_value']);
			if ($image) {
				$atts.=' image="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'" note="'.encodeXML($image->getNote()).'"';
			}
		}
		else if ($link['target_type']=='sameimage') {
			$image = Image::load($imageId);
			if ($image) {
				$atts.=' image="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'" note="'.encodeXML($image->getNote()).'"';
			}
		}
	    if ($link['path']!='') {
			$atts.=' path="'.encodeXML($link['path']).'"';
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
		if ($part['greyscale'] || $part['scalemethod']) {
			$dims = $this->calculateComputedDimensions($part,$image);
			$tag.='<transform display-width="'.$dims['width'].'" display-height="'.$dims['height'].'"';
			if ($part['greyscale']) {
				$tag.=' greyscale="true"';
			}
			if ($part['scalemethod']=='percent' && $part['scalepercent']) {
				$tag.=' scale-percent="'.$part['scalepercent'].'"';
			}
			elseif ($part['scalemethod']=='exact') {
				if ($part['scalewidth']) {
					$tag.=' width="'.$part['scalewidth'].'"';
				}
				if ($part['scaleheight']) {
					$tag.=' height="'.$part['scaleheight'].'"';
				}
			}
			elseif ($part['scalemethod']=='max') {
				if ($part['scalewidth']) {
					$tag.=' max-width="'.$part['scalewidth'].'"';
				}
				if ($part['scaleheight']) {
					$tag.=' max-height="'.$part['scaleheight'].'"';
				}
			}
			$tag.='/>';
		}
		return $tag;
	}
	
	
	// Toolbar stuff
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getMainToolbarBody() {
		return '
			<script source="../../Parts/image/toolbar.js"/>
			<divider/>
			<icon icon="common/new" title="Upload billede" name="addImage"/>
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
			</grid>
		';
	}
	
	function getToolbars() {
		return array('Link' =>
			'<grid>
				<row>
					<cell label="Side:" width="200" right="5">
						<dropdown name="page" adaptive="true">
							'.GuiUtils::buildPageItems().'
						</dropdown>
					</cell>
					<cell label="URL:" width="100">
						<textfield name="url"/>
					</cell>
				</row>
				<row>
					<cell label="Fil:" width="200" right="5">
						<dropdown name="file" adaptive="true">
							'.GuiUtils::buildObjectItems('file').'
						</dropdown>
					</cell>
					<cell label="E-mail:" width="100">
						<textfield name="email"/>
					</cell>
				</row>
			</grid>'
		);
	}
	
	function setLatestUploadId($id) {
		$_SESSION['part.image.latest_upload_id'] = $id;
	}
	
	function getLatestUploadId($id) {
		return $_SESSION['part.image.latest_upload_id'];
	}
}
?>