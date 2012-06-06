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
		'maptype' => array( 'type' => 'text' )
	)
);

class MapPart extends Part
{
	var $maptype;
	
	function MapPart() {
		parent::Part('map');
	}
	
	function load($id) {
		return Part::load('map',$id);
	}
	
	function setMaptype($maptype) {
	    $this->maptype = $maptype;
	}

	function getMaptype() {
	    return $this->maptype;
	}
	
	
}
?>