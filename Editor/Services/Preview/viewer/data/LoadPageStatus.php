<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');

In2iGui::sendObject(array(
	'changed'=>PageService::isChanged($id)
));
?>