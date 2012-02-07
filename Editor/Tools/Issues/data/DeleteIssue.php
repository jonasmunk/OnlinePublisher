<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

if ($object = Issue::load(Request::getId())) {
	$object->remove();
}
?>