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
	$src->setTitle($data->title);
	$src->setRecipe($data->recipe);
	$src->save();
	$src->publish();
}
?>