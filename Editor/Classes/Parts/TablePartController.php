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
require_once($basePath.'Editor/Classes/Parts/TablePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Services/SettingService.php');

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
		
	function editor($part,$context) {
		global $baseUrl;
		return
		'<div id="part_table">'.$this->render($part,$context).'</div>'.
		'<input type="hidden" name="html" value="'.StringUtils::escapeXML(StringUtils::fromUnicode($part->getHtml())).'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/table/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="Kilde" name="sourceWindow" width="500">
			<formula name="sourceFormula">
				<group labels="above">
					<text multiline="true" key="source" max-height="500"/>
				</group>
				<buttons>
					<button name="applySource" title="OK" click="sourceWindow.hide()"/>
				</buttons>
			</formula>
		</window>

		<window title="Egenskaber" name="propertiesWindow" icon="monochrome/info" width="300" padding="10">
			<formula name="propertiesFormula">
				<fieldset legen="Tabel">
					<group labels="before">
						<style-length key="width" label="Width"/>
					</group>
				</fieldset>
				<fieldset legen="Celle">
					<group labels="before">
						<text key="cellBackground" label="Baggrund"/>
					</group>
				</fieldset>
			</formula>
		</window>
		';
		return In2iGui::renderFragment($gui);
	}

	function getToolbars() {
		return array(
			'Tabel' => '
				<icon icon="common/clean" text="Ryd op" name="clean"/>
				<icon icon="common/info" text="Info" name="showInfo"/>
				<icon icon="file/generic" text="Kilde" overlay="edit" name="editSource"/>
				<divider/>
				<icon icon="file/generic" text="Ny række" overlay="add" name="addRow"/>
				<icon icon="file/generic" text="Ny kolonne" overlay="add" name="addColumn"/>
				'
			);
	}
	
	function getFromRequest($id) {
		$part = TablePart::load($id);
		$part->setHtml(Request::getUnicodeString('html'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$html = $part->getHtml();
		if (DOMUtils::isValidFragment($html)) {
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