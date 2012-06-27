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
		$part->setZoom(16);
		$part->setProvider('google-static');
		$part->setLatitude(48.85843994078143);
		$part->setLongitude(2.2944796085357666);
		$part->setFrame('shadow_slant');
		$part->setWidth('500px');
		$part->setHeight('300px');
		$part->save();
		Log::debug($part);
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
			"longitude" => $part->getLongitude(),
			"latitude" => $part->getLatitude(),
			"maptype" => $part->getMaptype(),
			"zoom" => $part->getZoom(),
			"text" => $part->getText(),
			"markers" => $part->getMarkers(),
			"mapwidth" => $part->getWidth(),
			"mapheight" => $part->getHeight(),
			"frame" => $part->getFrame()
		)).
		'<div id="part_map_container">'.
		$this->render($part,$context).
		'</div>
		<script src="'.$baseUrl.'Editor/Parts/map/editor.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		//partController.setMarkers('.StringUtils::fromJSON($part->getMarkers()).');
		</script>';
	}
	
	function getFromRequest($id) {
		$part = MapPart::load($id);
		$part->setProvider(Request::getUnicodeString('provider'));
		$part->setMaptype(Request::getUnicodeString('maptype'));
		$part->setMarkers(Request::getUnicodeString('markers'));
		$part->setLongitude(Request::getFloat('longitude'));
		$part->setLatitude(Request::getFloat('latitude'));
		$part->setZoom(Request::getInt('zoom'));
		$part->setText(Request::getUnicodeString('text'));
		$part->setWidth(Request::getString('mapwidth'));
		$part->setHeight(Request::getString('mapheight'));
		$part->setFrame(Request::getString('frame'));
		Log::debug($part);
		return $part;
	}
	
	function buildSub($part,$context) {
		$xml = '<map xmlns="'.$this->getNamespace().'" maptype="'.$part->getMaptype().'" zoom="'.$part->getZoom().'" provider="'.$part->getProvider().'"';
		if ($part->getFrame()) {
			$xml.=' frame="'.$part->getFrame().'"';
		}
		if ($part->getWidth()) {
			if ($part->getProvider()=='google-static') {
				$xml.=' width="'.intval($part->getWidth()).'"';
			} else {
				$xml.=' width="'.$part->getWidth().'"';
			}
		}
		if ($part->getHeight()) {
			if ($part->getProvider()=='google-static') {
				$xml.=' height="'.intval($part->getHeight()).'"';
			} else {
				$xml.=' height="'.$part->getHeight().'"';
			}
		}
		if ($part->getLongitude()) {
			$xml.=' longitude="'.$part->getLongitude().'"';
		}
		if ($part->getLatitude()) {
			$xml.=' latitude="'.$part->getLatitude().'"';
		}
		$xml.='>';
		if ($part->getText()) {
			$xml.='<text>'.StringUtils::escapeXML($part->getText()).'</text>';
		}
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
			$mapType = $map->getAttribute('maptype');
			if (StringUtils::isNotBlank($mapType)) {
				$part->setMaptype($mapType);
			}
			$longitude = $map->getAttribute('longitude');
			if (StringUtils::isNotBlank($longitude)) {
				$part->setLongitude(floatval($longitude));
			}
			$latitude = $map->getAttribute('latitude');
			if (StringUtils::isNotBlank($latitude)) {
				$part->setLatitude(floatval($latitude));
			}
		}
		
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="Indstillinger" name="mapWindow" width="300" padding="10">
			<formula name="mapFormula">
				<fields labels="above">
					<field label="Text">
						<text-input multiline="true" key="text"/>
					</field>
					<field label="Lokation">
						<location-input key="center"/>
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
							<item title="Satellit" value="satellite"/>
							<item title="Hybrid" value="hybrid"/>
						</dropdown>
					</field>
					<field label="Zoom">
						<number-input key="zoom" min="1" max="25"/>
					</field>
					<field label="Ramme">
						<dropdown key="frame">
							'.$this->getFrameOptions().'
						</dropdown>
					</field>
					<field label="Bredde">
						<style-length-input key="width"/>
					</field>
					<field label="Højde">
						<style-length-input key="height"/>
					</field>
				</fields>
				<buttons>
					<button name="currentLocation" text="Min lokation"/>
				</buttons>
			</formula>
		</window>
		';
		return In2iGui::renderFragment($gui);
	}
}
?>