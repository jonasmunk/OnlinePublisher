<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
if ($data->id) {
	$src = Workflow::load($data->id);
} else {
	$src = new Workflow();
}
if ($src) {
  if (isset($data->title)) {
    $src->setTitle($data->title);
  }
  if (isset($data->recipe)) {
    $src->setRecipe($data->recipe);
  }
	$src->save();
	$src->publish();
}
?>