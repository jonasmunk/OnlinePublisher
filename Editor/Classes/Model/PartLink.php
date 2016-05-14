<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['PartLink'] = [
	'table' => 'part_link',
	'properties' => [
        'id' => ['type' => 'int'],
		'partId' => ['type'=>'int','column'=>'part_id','relation'=>['class'=>'Part','property'=>'id']],
        'sourceType' => ['type' => 'string','column'=>'source_type'],
        'sourceText' => ['type' => 'string','column'=>'source_text'],
		'targetValue' => ['type'=>'string','column'=>'target_value','relations'=>[
			['class'=>'Page','property'=>'id'],
			['class'=>'File','property'=>'id']
            ]
        ],
        'targetType' => ['type' => 'string','column'=>'target_type']
    ]
];
class PartLink extends Entity {
	
	var $partId;
	var $sourceType;
	var $sourceText;
	var $targetType;
	var $targetValue;
		
	function setPartId($partId) {
	    $this->partId = $partId;
	}

	function getPartId() {
	    return $this->partId;
	}
    
    static function load($id) {
        return ModelService::load('PartLink',$id);
    }
	
	
	function setSourceType($sourceType) {
	    $this->sourceType = $sourceType;
	}

	function getSourceType() {
	    return $this->sourceType;
	}
	
	function setSourceText($sourceText) {
	    $this->sourceText = $sourceText;
	}

	function getSourceText() {
	    return $this->sourceText;
	}
	
	
	function setTargetType($targetType) {
	    $this->targetType = $targetType;
	}

	function getTargetType() {
	    return $this->targetType;
	}
	
	function setTargetValue($targetValue) {
	    $this->targetValue = $targetValue;
	}

	function getTargetValue() {
	    return $this->targetValue;
	}
	
	function save() {
		PartService::saveLink($this);
	}

	function remove() {
		PartService::removeLink($this);
	}
}