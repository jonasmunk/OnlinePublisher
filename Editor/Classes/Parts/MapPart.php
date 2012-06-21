<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['map'] = array(
	'fields' => array(
		'provider' => array( 'type' => 'text' ),
		'latitude' => array( 'type' => 'float' ),
		'longitude' => array( 'type' => 'float' ),
		'text' => array( 'type' => 'text' ),
		'maptype' => array( 'type' => 'text' ),
		'markers' => array( 'type' => 'text' ),
		'zoom' => array( 'type' => 'int' ),
		'width' => array( 'type' => 'text' ),
		'height' => array( 'type' => 'text' ),
		'frame' => array( 'type' => 'text' )
	)
);

class MapPart extends Part
{
	var $provider;
	var $latitude;
	var $longitude;
	var $text;
	var $maptype;
	var $markers;
	var $zoom;
	var $width;
	var $height;
	var $frame;
	
	function MapPart() {
		parent::Part('map');
	}
	
	function load($id) {
		return Part::load('map',$id);
	}
	
	function setLatitude($latitude) {
	    $this->latitude = $latitude;
	}

	function getLatitude() {
	    return $this->latitude;
	}
	
	function setLongitude($longitude) {
	    $this->longitude = $longitude;
	}

	function getLongitude() {
	    return $this->longitude;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setMaptype($maptype) {
	    $this->maptype = $maptype;
	}

	function getMaptype() {
	    return $this->maptype;
	}
	
	function setMarkers($markers) {
	    $this->markers = $markers;
	}

	function getMarkers() {
	    return $this->markers;
	}
	
	function setZoom($zoom) {
	    $this->zoom = $zoom;
	}

	function getZoom() {
	    return $this->zoom;
	}
	
	function setProvider($provider) {
	    $this->provider = $provider;
	}

	function getProvider() {
	    return $this->provider;
	}
	
	function setWidth($width) {
	    $this->width = $width;
	}

	function getWidth() {
	    return $this->width;
	}
	
	function setHeight($height) {
	    $this->height = $height;
	}

	function getHeight() {
	    return $this->height;
	}
	
	function setFrame($frame) {
	    $this->frame = $frame;
	}

	function getFrame() {
	    return $this->frame;
	}
	
}
?>