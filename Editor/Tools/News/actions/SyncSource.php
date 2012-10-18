<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$src = Newssource::load($id);
if ($src) {
	$src->synchronize(true);
}
?>