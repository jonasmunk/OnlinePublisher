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
	
	/** This function creates a new part */
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
		<link url="http://www.somewhere.com">Commodo Dolor Tristique</link>
	</page>
</pages>';
		$part->setRecipe($recipe);
		$part->save();
		return $part;
	}
	
	function updateAdditional($part) {
		PartService::removeLinks($part);
		$recipe = $part->getRecipe();
		$dom = DOMUtils::parse($recipe);
		if ($dom) {
			$links = $dom->getElementsByTagName('link');
			for ($i=0; $i < $links->length; $i++) { 
				$node = $links->item($i);
				$link = new PartLink();
				$link->setPartId($part->getId());
				$link->setSourceText(DOMUtils::getText($node));
				$types = array('url','email','page','file');
				foreach ($types as $type) {
					$value = $node->getAttribute($type);
					if ($value) {
						$link->setTargetType($type);
						$link->setTargetValue($value);
					}
				}
				$link->save();
			}
		}
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		$html =
		$this->buildHiddenFields(array(
			"recipe" => $part->getRecipe()
		)).
		'<div id="part_poster_container">'.
		$this->render($part,$context).
		'</div>
		<script src="'.$baseUrl.'Editor/Parts/poster/poster_editor.js" type="text/javascript" charset="utf-8"></script>';
		return $html;
	}

	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Poster','da'=>'Plakat')) => '
				<icon icon="common/previous" text="{Previous ; da:Forrige}" name="goPrevious"/>
				<icon icon="common/next" text="{Next ; da:Næste}" name="goNext"/>
				<divider/>
				<icon icon="file/generic" text="{Page ; da:Side}" name="showPageInfo"/>
				<icon icon="common/info" text="{Info ; da:Info}" name="showInfo"/>
				<icon icon="file/text" overlay="edit" text="{Source ; da:Kilde}" name="showSource"/>
				'
			);
	}
	
	function editorGui($part,$context) {
		$gui='
			<window title="{Poster;da:Plakat}" name="posterWindow" width="300">
			<!--
				<toolbar variant="window">
					<icon icon="common/previous" text="{Previous ; da:Forrige}" name="goPrevious"/>
					<icon icon="common/next" text="{Next ; da:Næste}" name="goNext"/>
					<divider/>
					<icon icon="common/info" text="{Page ; da:Side}" name="showPages"/>
					<icon icon="file/text" overlay="edit" text="{Source ; da:Kilde}" name="showSource"/>
				</toolbar>
				-->
				<formula name="posterFormula" padding="10">
					<fields labels="above">
						<field label="{Højde; da:Height}">
							<number-input key="height" allow-null="true" min="20" max="500"/>
						</field>
						<field label="{Appearance; da:Udseende}">
							<dropdown key="variant">
								<item value="" text="Standard"/>
								<item value="light" text="{Lys; da:Light}"/>
								<item value="inset" text="{Nedsunket; da:Inset}"/>
							</dropdown>
						</field>
					</fields>
				</formula>
			</window>

			<window title="{Page; da:Side}" name="pageWindow" width="300">
				<toolbar variant="window">
					<icon icon="common/move_left" text="{Move left; da:Flyt til venstre}" name="moveLeft"/>
					<icon icon="common/move_right" text="{Move right; da:Flyt til højre}" name="moveRight"/>
					<right>
						<icon icon="common/Delete" text="{Delete; da:Slet}" name="deletePage">
							<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
						</icon>
						<icon icon="common/new" text="{Add; da:Tilføj}" name="addPage"/>
					</right>
				</toolbar>
				<formula name="pageFormula" padding="10">
					<fields labels="above">
						<field label="{Title; da:Titel}">
							<text-input key="title"/>
						</field>
						<field label="{Text; da:Tekst}">
							<text-input multiline="true" key="text" max-height="500"/>
						</field>
						<field label="{Image; da:Billede}">
							<image-input key="image" source="../../Services/Model/ImagePicker.php">
								<finder title="{Select file; da:Vælg fil}" 
									list-url="../../Services/Finder/ImagesList.php"
									selection-url="../../Services/Finder/ImagesSelection.php"
									selection-value="all"
									selection-parameter="group"
									search-parameter="query"
								/>
							</image-input>
						</field>
						<field label="{Link text; da:Link tekst}:">
							<text-input key="linktext"/>
						</field>
						<field label="Link:">
							<object-input key="link">
								<type key="url" label="{Address; da:Adresse}"/>
								<type key="email" label="{E-mail; da:E-post}"/>
								<type key="page" label="{Page; da:Side}" icon="common/page" lookup-url="../../Services/Model/LoadPage.php">
									<finder title="{Select page; da:Vælg side}"
										list-url="../../Services/Finder/PagesList.php"
										selection-url="../../Services/Finder/PagesSelection.php"
										selection-value="all"
										selection-parameter="group"
										search-parameter="query"
									/>
								</type>
								<type key="file" label="{File; da:Fil}" icon="file/generic" lookup-url="../../Services/Model/LoadObject.php">
									<finder title="{Select file; da:Vælg fil}" 
										list-url="../../Services/Finder/FilesList.php"
										selection-url="../../Services/Finder/FilesSelection.php"
										selection-value="all"
										selection-parameter="group"
										search-parameter="query"
									/>
								</type>
							</object-input>
						</field>

					</fields>
				</formula>
			</window>
			
			<window title="{Source; da:Kilde}" name="sourceWindow" width="600">
				<formula name="sourceFormula">
					<code-input key="recipe"/>
				</formula>			
			</window>
			';
		return In2iGui::renderFragment($gui);
	}
	
	function getFromRequest($id) {
		$part = PosterPart::load($id);
		$part->setRecipe(Request::getString('recipe')); // do not use getEncodedString
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