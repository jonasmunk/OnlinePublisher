<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$ids = Request::getObject('ids');
foreach ($ids as $id) {
	$obj = Image::load($id);
	if ($obj) {
		$obj->remove();
	}	
}
?>