<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$src = NewsSource::load($id);
if ($src) {
	$src->synchronize(true);
}
?>