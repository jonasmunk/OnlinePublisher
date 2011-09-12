<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

function getPreviewReturn() {
	if (isset($_SESSION['services.preview.return'])) {
		return $_SESSION['services.preview.return'];
	}
	else {
		return 'Tools/Pages/';
	}
}
	
function setPreviewReturn($path) {
	$_SESSION['services.preview.return']=$path;
}
	
function setStickyDesignId($id) {
	$_SESSION['services.preview.sticky_design_id']=$id;
}

function getStickyDesignId() {
	if (isset($_SESSION['services.preview.sticky_design_id'])) {
		return $_SESSION['services.preview.sticky_design_id'];
	}
	else {
		return 0;
	}
}
?>