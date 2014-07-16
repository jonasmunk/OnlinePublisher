<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['MapPart'] = array(
	'table' => 'part_map',
	'properties' => array(
		'provider' => array( 'type' => 'string' ),
		'latitude' => array( 'type' => 'float' ),
		'longitude' => array( 'type' => 'float' ),
		'text' => array( 'type' => 'string' ),
		'maptype' => array( 'type' => 'string' ),
		'markers' => array( 'type' => 'string' ),
		'zoom' => array( 'type' => 'int' ),
		'width' => array( 'type' => 'string' ),
		'height' => array( 'type' => 'string' ),
		'frame' => array( 'type' => 'string' )
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
	
	static function load($id) {
		return Part::get('map',$id);
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