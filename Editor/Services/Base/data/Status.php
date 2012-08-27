<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Base
 */
require_once '../../../Include/Private.php';


Response::sendObject(array(
	'unpublished' => PublishingService::getTotalUnpublishedCount()
));

?>