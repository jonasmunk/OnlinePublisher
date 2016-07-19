<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
if ($data->id) {
	$src = View::load($data->id);
} else {
	$src = new View();
}
if ($src) {
  if (isset($data->title)) {
    $src->setTitle($data->title);
  }
  if (isset($data->path)) {
    $src->setPath($data->path);
  }
	$src->save();
	$src->publish();
}
?>