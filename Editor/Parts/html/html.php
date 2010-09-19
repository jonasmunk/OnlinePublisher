<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartHtml extends LegacyPartController {
	
	function PartHtml($id=0) {
		parent::LegacyPartController('html');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		if ($part = HtmlPart::load($this->id)) {
			return
			'<textarea id="PartHtmlTextarea" name="html" style="width: 100%; height: 300px; border: none;">'.
			StringUtils::escapeXML($part->getHtml()).
			'</textarea>'.
			'<script type="text/javascript">'.
			'document.getElementById("PartHtmlTextarea").focus();'.
			'document.getElementById("PartHtmlTextarea").select();'.
			'</script>';
		}
		return '';
	}
	
	function sub_update() {
		$html = Request::getString('html');
		if ($part = HtmlPart::load($this->id)) {
			$part->setHtml($html);
			$part->save();
		}
	}
	
	function sub_import(&$node) {
		$html = $node->getText();
		if ($part = HtmlPart::load($this->id)) {
			$part->setHtml($html);
			$part->save();
		}
	}
	
	function sub_build($context) {
		if ($part = HtmlPart::load($this->id)) {
			return 
			'<html xmlns="'.$this->_buildnamespace('1.0').'">'.
			'<![CDATA['.$part->getHtml().']]>'.
			'</html>';
		}
		return '';
	}
	
	function isIn2iGuiEnabled() {
		return true;
	}
}
?>