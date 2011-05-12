<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
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
		$part->setRecipe('<page><title>Min plakat</title><text>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</text></page>');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return
		'<textarea id="part_poster_textarea" name="recipe" style="width: 100%; height: 300px; border: none; padding: 0;">'.
		StringUtils::escapeXML($part->getRecipe()).
		'</textarea>'.
		'<script type="text/javascript">'.
		'document.getElementById("part_poster_textarea").focus();'.
		'document.getElementById("part_poster_textarea").select();'.
		'</script>';
	}
	
	function getFromRequest($id) {
		$part = PosterPart::load($id);
		$part->setRecipe(Request::getUnicodeString('recipe'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$valid = DOMUtils::isValidFragment($part->getRecipe());
		return 
		'<poster xmlns="'.$this->getNamespace().'">'.
		($valid ? '<recipe>'.$part->getRecipe().'</recipe>' : '<invalid/>').
		'</poster>';
	}
	
	function importSub($node,$part) {
		$recipe = DOMUtils::getFirstDescendant($node,'recipe');
		$xml = DOMUtils::getInnerXML($recipe);
		$xml = DOMUtils::stripNamespaces($xml);
		$part->setRecipe($xml);
	}
}
?>