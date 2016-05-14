<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../Editor/Include/Public.php';

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
	'background' => Request::getString('background'),
	'filters' => array()
);
if (!$recipe['method']) {
	$recipe['method'] = 'fit';
}
$parameters = Request::getParameters();
foreach ($parameters as $parameter) {
	$name = $parameter['name'];
	$value = $parameter['value'];
	if ($name === 'sharpen') {
		$recipe['filters'][] = array('name' => 'sharpen','amount' => ($value==='true' ? 1 : floatval($value)));
	} else if ($name === 'sharpen') {
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
	$sql = 'select `filename`,`type`,`width`,`height` from image where object_id='.Database::int($id);
	if ($row = Database::selectFirst($sql)) {
		$path = ConfigurationService::getImagePath($row['filename']);
    if (file_exists($path)) {
  		ImageTransformationService::sendFile($path,$row['type']);
    } else if (ConfigurationService::isDebug()) {
      Response::redirect(getPlaceholder($recipe,$row));
    } else {
	    Response::notFound();
    }
	}
	exit;
}

$cache = ImageTransformationService::buildCachePath($id,$recipe);
if (file_exists($cache)) {
	ImageTransformationService::sendFile($cache,$recipe['format']);
} else {
	$sql = 'select `filename`,`type`,`width`,`height` from image where object_id='.Database::int($id);
	if ($row = Database::selectFirst($sql)) {
		$recipe['path'] = ConfigurationService::getImagePath($row['filename']);
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
    $height = isset($recipe['height']) ? $recipe['height'] : $row['height'];
    $width = isset($recipe['width']) ? $recipe['width'] : $row['width'];
    return 'http://placeimg.com/' . $width . '/' . $height . '/arch/grayscale';
}
?>