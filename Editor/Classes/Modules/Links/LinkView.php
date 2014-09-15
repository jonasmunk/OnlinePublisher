<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkView {
	
	static $TARGET_NOT_FOUND = 'notfound';
	static $TEXT_NOT_FOUND = 'textnotfound';
	static $INVALID_ADDRESS = 'invalidaddress';
	
	private $id;
	private $type;
	
	private $sourceType;
	private $sourceId;
	private $sourceTitle;
	private $sourceDescription;
	
	private $sourceSubType;
	private $sourceSubId;
	
	private $sourceText;
	
	private $targetType;
	private $targetId;
	private $targetTitle;
	
	private $errors = array();
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
    function setType($type) {
        $this->type = $type;
    }
    
    function getType() {
        return $this->type;
    }
    
	
	function setSourceType($sourceType) {
	    $this->sourceType = $sourceType;
	}

	function getSourceType() {
	    return $this->sourceType;
	}
	
	function setSourceId($sourceId) {
	    $this->sourceId = $sourceId;
	}

	function getSourceId() {
	    return $this->sourceId;
	}
	
	function setSourceSubId($sourceSubId) {
	    $this->sourceSubId = $sourceSubId;
	}

	function getSourceSubId() {
	    return $this->sourceSubId;
	}
	
	function setSourceSubType($sourceSubType) {
	    $this->sourceSubType = $sourceSubType;
	}

	function getSourceSubType() {
	    return $this->sourceSubType;
	}
	
	
	
	function setSourceTitle($sourceTitle) {
	    $this->sourceTitle = $sourceTitle;
	}

	function getSourceTitle() {
	    return $this->sourceTitle;
	}
	
	function setSourceText($sourceText) {
	    $this->sourceText = $sourceText;
	}

	function getSourceText() {
	    return $this->sourceText;
	}
	
	function setSourceDescription($sourceDescription) {
	    $this->sourceDescription = $sourceDescription;
	}

	function getSourceDescription() {
	    return $this->sourceDescription;
	}
	
	
	
	function setTargetType($targetType) {
	    $this->targetType = $targetType;
	}

	function getTargetType() {
	    return $this->targetType;
	}
	
	function setTargetId($targetId) {
	    $this->targetId = $targetId;
	}

	function getTargetId() {
	    return $this->targetId;
	}
	
	function setTargetTitle($targetTitle) {
	    $this->targetTitle = $targetTitle;
	}

	function getTargetTitle() {
	    return $this->targetTitle;
	}
	
	function getErrors() {
	    return $this->errors;
	}
	
	function addError($key,$message) {
		$this->errors[] = array('key'=>$key,'message'=>$message);
	}
	
	function hasError($key=null) {
		if ($key===null && count($this->errors)>0) {
			return true;
		}
		foreach ($this->errors as $error) {
			if ($error['key']==$key) {
				return true;
			}
		}
		return false;
	}
}