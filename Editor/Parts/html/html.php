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
	
	function sub_import(&$node) {
		$html = $node->getText();
		if ($part = HtmlPart::load($this->id)) {
			$part->setHtml($html);
			$part->save();
		}
	}
	
}
?>