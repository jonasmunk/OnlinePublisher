<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

interface ModelEventListener {

    static function objectCreated($event);

    static function objectDeleted($event);

    static function objectUpdated($event);

    static function objectPublished($event);
    
    static function hierarchyUpdated($event);
    
}
?>