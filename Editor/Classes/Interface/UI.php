<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class UI {
	
	static function renderFragment($gui) {
        return In2iGui::renderFragment($gui);
    }
}