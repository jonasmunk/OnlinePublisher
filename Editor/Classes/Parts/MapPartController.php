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
		$part->setZoom(11);
		$part->setProvider('google-static');
		$part->setWidth('500px');
		$part->setHeight('300px');
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
			"provider" => $part->getProvider(),
			"maptype" => $part->getMaptype(),
			"zoom" => $part->getZoom(),
			"markers" => $part->getMarkers(),
			"mapwidth" => $part->getWidth(),
			"mapheight" => $part->getHeight()
		)).
		'<div id="part_map_container">'.
		$this->render($part,$context).
		'</div>
		<script src="'.$baseUrl.'Editor/Parts/map/editor.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		partController.setMarkers('.StringUtils::fromJSON($part->getMarkers()).');
		</script>';
	}
	
	function getFromRequest($id) {
		$part = MapPart::load($id);
		$part->setProvider(Request::getUnicodeString('provider'));
		$part->setMaptype(Request::getUnicodeString('maptype'));
		$part->setMarkers(Request::getUnicodeString('markers'));
		$part->setZoom(Request::getInt('zoom'));
		$part->setWidth(Request::getString('mapwidth'));
		$part->setHeight(Request::getString('mapheight'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$xml = '<map xmlns="'.$this->getNamespace().'" maptype="'.$part->getMaptype().'" zoom="'.$part->getZoom().'">'.
		$markers = StringUtils::fromJSON(StringUtils::toUnicode($part->getMarkers()));
		if (is_array($markers)) {
			foreach ($markers as $marker) {
				if ($marker->point) {
					$xml.='<marker latitude="'.$marker->point->latitude.'" longitude="'.$marker->point->longitude.'"/>';
				}
			}
		}
		$xml.= '</map>';
		return $xml;
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
						<text-input multiline="true" key="text"/>
					</field>
					<field label="Lokation">
						<location-input key="point"/>
					</field>
				</fields>
				<fields>
					<field label="Udbyder">
						<dropdown key="provider">
							<item title="Google - statisk" value="google-static"/>
							<item title="Google - interaktivt" value="google-interactive"/>
						</dropdown>
					</field>
					<field label="Type">
						<dropdown key="maptype">
							<item title="Veje" value="roadmap"/>
							<item title="Terræn" value="terrain"/>
						</dropdown>
					</field>
					<field label="Zoom">
						<number-input key="zoom" min="1" max="25"/>
					</field>
					<field label="Bredde">
						<style-length-input key="width"/>
					</field>
					<field label="Højde">
						<style-length-input key="height"/>
					</field>
				</fields>
			</formula>
		</window>
		';
		return In2iGui::renderFragment($gui);
	}
}
?>