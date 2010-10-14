<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/HtmlPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HtmlPartController extends PartController
{
	function HtmlPartController() {
		parent::PartController('html');
	}
	
	function createPart() {
		$part = new HtmlPart();
		$part->setHtml('<div>HTML-kode</div>');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return
		'<textarea id="PartHtmlTextarea" name="html" style="width: 100%; height: 300px; border: none; padding: 0;">'.
		StringUtils::escapeXML($part->getHtml()).
		'</textarea>'.
		'<script type="text/javascript">'.
		'document.getElementById("PartHtmlTextarea").focus();'.
		'document.getElementById("PartHtmlTextarea").select();'.
		'</script>';
	}
	
	function getFromRequest($id) {
		$part = HtmlPart::load($id);
		$part->setHtml(Request::getString('html'));
		return $part;
	}
	
	function buildSub($part,$context) {
		return 
		'<html xmlns="'.$this->getNamespace().'">'.
		'<![CDATA['.$part->getHtml().']]>'.
		'</html>';
	}
	
	function importSub($node,$part) {
		if ($html = DOMUtils::getFirstDescendant($node,'html')) {
			if ($child = $html->firstChild) {
				$part->setHtml($child->data);
			}
		}
		
	}
}
?>