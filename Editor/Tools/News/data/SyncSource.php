<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$id = Request::getInt('id');

$src = NewsSource::load($id);
if ($src) {
	$src->synchronize(true);
}
?>