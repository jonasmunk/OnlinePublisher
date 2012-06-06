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
require_once($basePath.'Editor/Classes/Parts/MapPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class MapPartController extends PartController
{
	function MapPartController() {
		parent::PartController('map');
	}
	
	function createPart() {
		$part = new MapPart();
		$part->setMaptype('roadmap');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
		'<input type="hidden" name="maptype" value="'.StringUtils::escapeXML($part->getMaptype()).'"/>'.
		'<div id="part_map_container">'.
		$this->render($part,$context).
		'</div>'.
		'<script src="'.$baseUrl.'Editor/Parts/map/editor.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$part = MapPart::load($id);
		$part->setMaptype(Request::getUnicodeString('maptype'));
		return $part;
	}
	
	function buildSub($part,$context) {
		return 
		'<map xmlns="'.$this->getNamespace().'" maptype="'.$part->getMaptype().'">'.
		'</map>';
	}
	
	function importSub($node,$part) {
		if ($map = DOMUtils::getFirstDescendant($node,'map')) {
			$part->setMaptype($map->getAttribute('maptype'));
		}
		
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="Indstillinger" name="mapWindow" width="300" padding="10">
			<formula name="mapFormula">
				<fields labels="above">
					<field label="Adresse">
						<text-input multiline="true" key="address"/>
					</field>
				</fields>
				<fields>
					<field label="Type">
						<dropdown key="maptype">
							<item title="Veje" value="roadmap"/>
							<item title="Terræn" value="terrain"/>
						</dropdown>
					</field>
				</fields>
			</formula>
		</window>
		';
		return In2iGui::renderFragment($gui);
	}
}
?>