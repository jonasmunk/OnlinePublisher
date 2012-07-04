<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/PosterPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PosterPartController extends PartController
{
	function PosterPartController() {
		parent::PartController('poster');
	}
	
	function createPart() {
		$part = new PosterPart();
		$imageId = ObjectService::getLatestId('image');
		$recipe = '<pages>
	<page>
		'.($imageId ? '<image id="'.$imageId.'"/>' : '').'
		<title>Vehicula Tellus Tristique Ornare</title>
		<text>Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</text>
	</page>
	<page>
		<title>Cras Mollis Vestibulum Lorem</title>
		<text>Nullam quis risus eget urna mollis ornare vel eu leo. Etiam porta sem malesuada magna mollis euismod.</text>
	</page>
</pages>';
		$part->setRecipe($recipe);
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
		$this->buildHiddenFields(array(
			"recipe" => $part->getRecipe()
		)).
		'<div id="part_poster_container">'.
		$this->render($part,$context).
		'</div>
		<script src="'.$baseUrl.'Editor/Parts/poster/poster_editor.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function editorGui($part,$context) {
		$gui='
			<window title="Side" name="pageWindow" width="300">
				<toolbar variant="window">
					<icon icon="common/previous" text="Move left" name="moveLeft"/>
					<icon icon="common/next" text="Move right" name="moveRight"/>
					<right>
						<icon icon="common/Delete" text="Delete" name="deletePage"/>
						<icon icon="common/new" text="Add" name="addPage"/>
					</right>
				</toolbar>
				<formula name="pageFormula" padding="10">
					<fields labels="above">
						<field label="Title">
							<text-input key="title"/>
						</field>
						<field label="Text">
							<text-input multiline="true" key="text" max-height="500"/>
						</field>
						<field label="Image">
							<image-input key="image" source="../../Services/Model/ImagePicker.php"/>
						</field>
					</fields>
					<buttons>
						<button name="savePage" title="{Save ; da: Gem}" click="sourceWindow.hide()"/>
					</buttons>
				</formula>
			</window>
			
			<window title="Kilde" name="sourceWindow" width="600">
				<formula name="sourceFormula">
					<code-input key="recipe"/>
					<buttons right="3" bottom="2" top="1">
						<button name="applySource" title="{Save ; da: Gem}"/>
					</buttons>
				</formula>			
			</window>
			';
		return In2iGui::renderFragment($gui);
	}
	
	function getFromRequest($id) {
		$part = PosterPart::load($id);
		$part->setRecipe(Request::getUnicodeString('recipe')); // do not use getEncodedString
		return $part;
	}
	
	function buildSub($part,$context) {
		// Important to convert to unicode before validating
		$valid = DOMUtils::isValidFragment(StringUtils::toUnicode($part->getRecipe()));
		$xml =
		'<poster xmlns="'.$this->getNamespace().'">'.
		($valid ? '<recipe>'.$part->getRecipe().'</recipe>' : '<invalid/>').
		'</poster>';
		return $xml;
	}
	
	function importSub($node,$part) {
		$recipe = DOMUtils::getFirstDescendant($node,'recipe');
		$xml = DOMUtils::getInnerXML($recipe);
		$xml = DOMUtils::stripNamespaces($xml);
		$part->setRecipe($xml);
	}
}
?>