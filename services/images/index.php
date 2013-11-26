<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../Editor/Include/Public.php';

session_set_cookie_params(0);
session_start();

if (@$_SESSION['core.debug.simulateLatency']) {
	usleep(rand(1000000,2000000));
}
$id = Request::getId();

$recipe = array(
	'width' => Request::getInt('width',null),
	'height' => Request::getInt('height',null),
	'scale' => Request::getInt('scale',null),
	'quality' => Request::getInt('quality',90),
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
	} else if ($name == 'border') {
		$recipe['filters'][] = array('name' => 'border', 'width' => intval($value));
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
            if (file_exists($cache)) {
    			ImageTransformationService::sendFile($cache,$recipe['format']);                
        	} else if (ConfigurationService::isDebug()) {
                Response::redirect(getPlaceholder($recipe,$row));
            } else {
        		Response::internalServerError('Unable to transform image');
            }
		}
	} else if (ConfigurationService::isDebug()) {
        Response::redirect(getPlaceholder($recipe,$row));
    } else {
		Response::notFound();
	}
}

function getPlaceholder($recipe,$row) {
    $height = $recipe['height'] | $row['height'];
    $width = $recipe['width'] | $row['height'];
    Log::error('hey');
    return 'http://placeimg.com/' . $height . '/' . $width . '/arch/grayscale';
}
?>