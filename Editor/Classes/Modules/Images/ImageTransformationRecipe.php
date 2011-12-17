<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Images
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ImageTransformationRecipe {
	
	var $width;
	var $height;
	var $scale;
	var $method;
	var $filters = array();
	var $format;
}