<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Image.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');
require_once($basePath.'Editor/Classes/Log.php');

class PartImage extends LegacyPartController {
	
	function PartImage($id=0) {
		parent::LegacyPartController('image');
		$this->id = $id;
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
		
	function setLatestUploadId($id) {
		$_SESSION['part.image.latest_upload_id'] = $id;
	}
	
	function getLatestUploadId() {
		return $_SESSION['part.image.latest_upload_id'];
	}
}
?>