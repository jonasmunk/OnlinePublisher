<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Warnings
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Inspection {
	
	var $text;
	var $key;
	var $entity;
	var $status;
	var $category;
    var $info;
	
	function setStatus($status) {
	    $this->status = $status;
	}

	function getStatus() {
	    return $this->status;
	}
	
	function setCategory($category) {
	    $this->category = $category;
	}

	function getCategory() {
	    return $this->category;
	}
	

	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setKey($key) {
	    $this->key = $key;
	}

	function getKey() {
	    return $this->key;
	}
	
	function setEntity($entity) {
	    $this->entity = $entity;
	}

	function getEntity() {
	    return $this->entity;
	}
  
    function setInfo($info) {
        $this->info = $info;
    }

    function getInfo() {
        return $this->info;
    }
  
}