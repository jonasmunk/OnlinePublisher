<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Request.php');

class PartFormula extends LegacyPartController {
	
	function PartFormula($id=0) {
		parent::LegacyPartController('formula');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		if ($part=FormulaPart::load($this->id)) {
			return 
			'<input type="hidden" name="receiverName" value="'.escapeHTML($part->getReceiverName()).'"/>'.
			'<input type="hidden" name="receiverEmail" value="'.escapeHTML($part->getReceiverEmail()).'"/>'.
			$this->render();
		}
		Log::debug($this->id);
		return '';
	}
	
	function sub_update() {
		$name = Request::getString('receiverName');
		$email = Request::getString('receiverEmail');
		if ($part=FormulaPart::load($this->id)) {
			$part->setReceiverName($name);
			$part->setReceiverEmail($email);
			$part->save();
		}
	}
	
	function sub_build($context) {
		return '<formula xmlns="'.$this->_buildnamespace('1.0').'"/>';
	}

}
?>