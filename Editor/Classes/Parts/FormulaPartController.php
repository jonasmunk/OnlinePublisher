<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class FormulaPartController extends PartController
{
	function FormulaPartController() {
		parent::PartController('formula');
	}
	
	function createPart() {
		$part = new FormulaPart();
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return 
		'<input type="hidden" name="receiverName" value="'.StringUtils::escapeXML($part->getReceiverName()).'"/>'.
		'<input type="hidden" name="receiverEmail" value="'.StringUtils::escapeXML($part->getReceiverEmail()).'"/>'.
		$this->render($part,$context);
	}
	
	function getFromRequest($id) {
		$part = FormulaPart::load($id);
		$part->setReceiverName(Request::getString('receiverName'));
		$part->setReceiverEmail(Request::getString('receiverEmail'));
		return $part;
	}
	
	function buildSub($part,$context) {
		return '<formula xmlns="'.$this->getNamespace().'"/>';
	}
	
	function getToolbars() {
		return array(
			'Formular' => '
			<script source="../../Parts/formula/toolbar.js"/>
			<grid>
				<row>
					<cell label="Modtager navn:" width="180">
						<textfield adaptive="true" name="receiverName"/>
					</cell>
				</row>
				<row>
					<cell label="Modtager e-mail:" width="180">
						<textfield adaptive="true" name="receiverEmail"/>
					</cell>
				</row>
			</grid>
		');
	}
}
?>