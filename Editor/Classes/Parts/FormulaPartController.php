<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class FormulaPartController extends PartController
{
	function FormulaPartController() {
		parent::PartController('formula');
	}
	
	function createPart() {
		$part = new FormulaPart();
		$part->setRecipe('<form>
	<field label="Text field"><input/></field>
	<field label="Text field with multiple lines">
		<input line-breaks="true"/>
	</field>
	<space height="10"/>
	<fieldset legend="This is a fieldset">
		<space height="10"/>
		<columns>
			<column>
				<field label="A number field"><number/></field>
			</column>
			<column>
				<field label="A number field"><number/></field>
			</column>
		</columns>
	</fieldset>
	<submit text="Send message"/>
</form>');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return
		$this->buildHiddenFields(array(
			"receiverName" => $part->getReceiverName(),
			"receiverEmail" => $part->getReceiverEmail(),
			"recipe" => $part->getRecipe()
		)).
		'<div id="part_formula_container">'.
		$this->render($part,$context).
		'</div>'.
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/formula/formula_editor.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$part = FormulaPart::load($id);
		$part->setReceiverName(Request::getString('receiverName'));
		$part->setReceiverEmail(Request::getString('receiverEmail'));
		$part->setRecipe(Request::getString('recipe'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$valid = DOMUtils::isValidFragment(StringUtils::toUnicode($part->getRecipe()));
		return '<formula xmlns="'.$this->getNamespace().'">'.
			($valid ? '<recipe>'.$part->getRecipe().'</recipe>' : '<invalid/>').
			'</formula>';
	}
	
	function importSub($node,$part) {
		$recipe = DOMUtils::getFirstDescendant($node,'recipe');
		$xml = DOMUtils::getInnerXML($recipe);
		$xml = DOMUtils::stripNamespaces($xml);
		$part->setRecipe($xml);
	}
	
	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Formula','da'=>'Formular')) => '
			<script source="../../Parts/formula/toolbar.js"/>
			<icon icon="file/text" overlay="edit" text="{Show source;da:Vis kilde}" name="showSource"/>
			<divider/>
			<grid>
				<row>
					<cell label="{Receivers name; da:Modtager navn}:" width="180">
						<text-input adaptive="true" name="receiverName"/>
					</cell>
				</row>
				<row>
					<cell label="{Receivers e-mail; da:Modtagers e-post}:" width="180">
						<text-input adaptive="true" name="receiverEmail"/>
					</cell>
				</row>
			</grid>
		');
	}
	
	function editorGui($part,$context) {
		$gui='
			<window title="{Source; da:Kilde}" name="sourceWindow" width="600">
				<formula name="sourceFormula">
					<code-input key="recipe"/>
				</formula>			
			</window>
			';
		return In2iGui::renderFragment($gui);
	}
}
?>