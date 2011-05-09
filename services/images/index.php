<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once '../../Editor/Classes/Services/FileSystemService.php';
require_once '../../Editor/Classes/Modules/Images/ImageTransformationService.php';
require_once '../../Editor/Classes/Database.php';
require_once '../../Editor/Classes/Response.php';
require_once '../../Editor/Classes/Request.php';

session_set_cookie_params(0);
session_start();

if ($_SESSION['core.debug.simulateLatency']) {
	usleep(rand(1000000,2000000));
}
$id = Request::getId();

$recipe = array(
	'width' => Request::getInt('width',null),
	'height' => Request::getInt('height',null),
	'scale' => Request::getInt('scale',null),
	'quality' => Request::getInt('quality',null),
	'method' => Request::getString('method'),
	'format' => Request::getString('format'),
	'filters' => array()
);
if (!$recipe['method']) {
	$recipe['method'] = 'fit';
}
$parameters = Request::getParameters();
foreach ($parameters as $parameter) {
	$name = $parameter['name'];
	$value = $parameter['value'];
	if ($name === 'sharpen' && $value==='true') {
		$recipe['filters'][] = array('name' => 'sharpen');
	} else if ($name === 'greyscale' && $value==='true') {
		$recipe['filters'][] = array('name' => 'greyscale');
	} else if ($name === 'blur') {
		$recipe['filters'][] = array('name' => 'blur', 'amount' => intval($value));
	} else if ($name === 'contrast') {
		$recipe['filters'][] = array('name' => 'contrast', 'amount' => intval($value));
	} else if ($name === 'brightness') {
		$recipe['filters'][] = array('name' => 'brightness', 'amount' => intval($value));
	}
}
// Bypass transformation if not required
if ($recipe['width'] == null && $recipe['height'] == null && $recipe['scale'] == null && count($recipe['filters']) == 0 && !$recipe['format']) {
	$sql = 'select `filename`,`type` from image where object_id='.Database::int($id);
	if ($row = Database::selectFirst($sql)) {
		$path = $basePath.'images/'.$row['filename'];
		ImageTransformationService::sendFile($path,$row['type']);
	}
	exit;
}

$cache = ImageTransformationService::buildCachePath($id,$recipe);
if (file_exists($cache)) {
	ImageTransformationService::sendFile($cache,$recipe['format']);
} else {
	$sql = 'select `filename`,`type` from image where object_id='.Database::int($id);
	if ($row = Database::selectFirst($sql)) {
		$recipe['path'] = $basePath.'images/'.$row['filename'];
		if (Request::getBoolean('nocache')) {
			ImageTransformationService::transform($recipe);
		} else {
			$recipe['destination'] = $cache;
			ImageTransformationService::transform($recipe);
			ImageTransformationService::sendFile($cache,$recipe['format']);
		}
	} else {
		Response::notFound();
	}
}
?>