<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
		
class Entity {
	
    var $id;
	static $schema = array();

    function setId($id) {
        $this->id = $id;
    }
    
    function getId() {
        return $this->id;
    }

}