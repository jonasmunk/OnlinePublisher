<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['MenuPart'] = [
	'table' => 'part_menu',
	'properties' => [
		'hierarchyId' => [ 'type' => 'int', 'column' => 'hierarchy_id', 'relation' => ['class' => 'Hierarchy','property' => 'id'] ],
		'variant' => [ 'type' => 'string' ],
        'depth' => [ 'type' => 'int' ],
	]
];

class MenuPart extends Part
{
	var $hierarchyId;
	var $variant;
    var $depth;

	function MenuPart() {
		parent::Part('menu');
	}

	static function load($id) {
		return Part::get('menu',$id);
	}

    function setHierarchyId($hierarchyId) {
        $this->hierarchyId = $hierarchyId;
    }

    function getHierarchyId() {
        return $this->hierarchyId;
    }

    function setVariant($variant) {
        $this->variant = $variant;
    }

    function getVariant() {
        return $this->variant;
    }
    
    function setDepth($depth) {
        $this->depth = $depth;
    }
    
    function getDepth() {
        return $this->depth;
    }
    
}
?>