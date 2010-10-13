<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartPerson extends LegacyPartController {
	
	function PartPerson($id=0) {
		parent::LegacyPartController('person');
		$this->id = $id;
	}
	
	function sub_import(&$node) {
		$object =& $node->selectNodes('object',1);
		$sql = "update part_person set".
		" person_id=".Database::int($object->getAttribute('id')).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
}
?>