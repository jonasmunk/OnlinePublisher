<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Richtext
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartRichtext extends LegacyPartController {
	
	function PartRichtext($id=0) {
		parent::LegacyPartController('richtext');
		$this->id = $id;
	}

	function sub_import(&$node) {
		$html = '';
		$c =& $node->childNodes;
		for ($i=0;$i<$node->childCount;$i++) {
			$html.=$c[$i]->toString();
		}
		$sql = "update part_richtext set".
		" html=".Database::text($html).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
}
?>