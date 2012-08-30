<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Link {
    
    var $id;
    var $text;
	var $alternative;
	var $targetType;
	var $targetValue;
	var $targetId;
	var $pageId;
	var $partId;
    
    function Link() {
        
    }

	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setPageId($pageId) {
	    $this->pageId = $pageId;
	}

	function getPageId() {
	    return $this->pageId;
	}
	
	function setPartId($partId) {
	    $this->partId = $partId;
	}

	function getPartId() {
	    return $this->partId;
	}
	
	function getTargetType() {
	    return $this->targetType;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setAlternative($alternative) {
	    $this->alternative = $alternative;
	}

	function getAlternative() {
	    return $this->alternative;
	}
	
	function setTypeAndValue($type,$value) {
		if ($type=='page' || $type=='file') {
			$this->targetType = $type;
			$this->targetId=intval($value);
			$this->targetValue=null;
		} else if ($type=='url' || $type=='email') {
			$this->targetType = $type;
			$this->targetValue=$value;
			$this->targetId=null;
		}
	}
	
	function getUrl() {
		if ($this->targetType=='url') {
			return $this->targetValue;
		} else {
			return '';
		}
	}
	
	function getEmail() {
		if ($this->targetType=='email') {
			return $this->targetValue;
		} else {
			return '';
		}
	}
	
	function getPage() {
		if ($this->targetType=='page') {
			return $this->targetId;
		} else {
			return null;
		}
	}
	
	function getFile() {
		if ($this->targetType=='file') {
			return $this->targetId;
		} else {
			return null;
		}
	}

	function load($id) {
		return LinkService::load($id);
	}
	
	function remove() {
		return LinkService::remove($this);
	}
	
	function save() {
		LinkService::save($this);
	}

}