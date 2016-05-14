<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TablePartController extends PartController
{
	function TablePartController() {
		parent::PartController('table');
	}
	
	function createPart() {
		$part = new TablePart();
		$part->setHtml('<table><thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><tr><td>Cell</td><td>Cell</td></tr><tr><td>Cell</td><td>Cell</td></tr></tbody></table>');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function getIndex($part) {
		return Strings::convertMarkupToText($part->getHtml());
	}
		
	function editor($part,$context) {
		return
		'<div id="part_table" class="part_table common_font" style="min-height: 20px;">'.$part->getHtml().'</div>'.
		'<input type="hidden" name="html" value="'.Strings::escapeSimpleXML($part->getHtml()).'"/>'. // Important not to use Strings::escapeXML since it messes with unicode chars
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/table/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="{Source; da:Kilde}" name="sourceWindow" width="500">
			<formula name="sourceFormula">
				<code-input key="source"/>
			</formula>
		</window>

		<window title="{Properties; da:Egenskaber}" name="propertiesWindow" icon="monochrome/info" width="300" padding="10">
			<formula name="propertiesFormula">
				<fieldset legend="{Table; da:Tabel}">
					<fields labels="before">
						<!--
						<field label="Variant">
							<dropdown key="variant">
								<item text="Moderne"/>
								<item text="Markant"/>
							</dropdown>
						</field>
						-->
						<field label="{Head; da:Hoved}">
							<dropdown key="head" name="tableHead">
								<item text="{None; da:Ingen}" value="0"/>
								<item text="{1 row; da:1 række}" value="1"/>
								<item text="{2 rows; da:2 rækker}" value="2"/>
								<item text="{3 rows; da:3 rækker}" value="3"/>
								<item text="{4 rows; da:4 rækker}" value="4"/>
								<item text="{5 rows; da:5 rækker}" value="5"/>
							</dropdown>
						</field>
						<field label="{Footer; da:Bund}">
							<dropdown key="foot" name="tableFoot">
								<item text="{None; da:Ingen}" value="0"/>
								<item text="{1 row; da:1 række}" value="1"/>
								<item text="{2 rows; da:2 rækker}" value="2"/>
								<item text="{3 rows; da:3 rækker}" value="3"/>
								<item text="{4 rows; da:4 rækker}" value="4"/>
								<item text="{5 rows; da:5 rækker}" value="5"/>
							</dropdown>
						</field>
						<field label="{Width; da: Bredde}">
							<style-length-input key="width"/>
						</field>
					</fields>
				</fieldset>
				<!--
				<space height="10"/>
				<fieldset legend="{Cell; da:Celle}">
					<fields labels="before">
						<field label="{Background; da:Baggrund}">
							<text-input key="cellBackground"/>
						</field>
					</fields>
				</fieldset>
				-->
			</formula>
		</window>
		
		<menu name="tableMenu">
			<item text="{Delete row; da:Slet række}" value="deleteRow"/>
			<item text="{Move up; da:Flyt op}" value="moveUp"/>
			<item text="{Move down; da:Flyt ned}" value="moveDown"/>
			<divider/>
			<item text="{Delete column; da:Slet kolonne}" value="deleteRow"/>
			<item text="{Move left; da:Flyt til venstre}" value="moveLeft"/>
			<item text="{Move right; da:Flyt til højre}" value="moveRight"/>
		</menu>


		';
		return UI::renderFragment($gui);
	}

	function getToolbars() {
		return array(
			'Tabel' => '
				<icon icon="common/clean" text="{Clean; da:Ryd op}" name="clean"/>
				<icon icon="common/info" text="Info" name="showInfo"/>
				<icon icon="file/text" text="{Source; da:Kilde}" overlay="edit" name="editSource"/>
				<divider/>
				<icon icon="table/row" text="{New row; da:Ny række}" overlay="new" name="addRow"/>
				<icon icon="table/column" text="{New column; da:Ny kolonne}" overlay="new" name="addColumn"/>
				'
			);
	}
	
	function getFromRequest($id) {
		$part = TablePart::load($id);
		$html = Request::getString('html');
		$html = str_replace('contenteditable="true"', '', $html);
		$part->setHtml($html);
		return $part;
	}
	
	function buildSub($part,$context) {
		$html = $part->getHtml();
		if (DOMUtils::isValidFragment($html)) {
			$html = $this->insertLinks($part,$context);
			//$html = $context->decorateForBuild($html,$part->getId());
			return '<table xmlns="'.$this->getNamespace().'" valid="true">'.
			$html.
			'</table>';
		} else {
			return 
			'<table xmlns="'.$this->getNamespace().'" valid="false">'.
			'<![CDATA['.$html.']]>'.
			'</table>';
		}
	}
	
	function insertLinks($part,$context) {
		$html = $part->getHtml();
		preg_match_all("/<[^>]+>/u",$html,$matches,PREG_OFFSET_CAPTURE);
		$out = '';
		$index = 0;
		foreach ($matches[0] as $found) {
			if ($found[1]-$index > 0) {
				$str = substr($html,$index,$found[1]-$index);
				$str = $context->decorateForBuild($str,$part->getId());
				$out.=$str;
			}
			$index = $found[1] + strlen($found[0]);
			$out.= $found[0];
		}
		return $out;
	}
	
	function importSub($node,$part) {
		if ($table = DOMUtils::getFirstDescendant($node,'table')) {
			if ($table->getAttribute('valid')=='false') {
				$part->setHtml(DOMUtils::getText($table));
			} else {
				$str = DOMUtils::getInnerXML($table);
				$str = DOMUtils::stripNamespaces($str);
				$part->setHtml($str);
			}
		}
		
	}
}
?>