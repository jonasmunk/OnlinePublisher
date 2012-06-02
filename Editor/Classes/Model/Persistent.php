<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
interface Persistent {
	
	public function load($id);
	public function save();
}