<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Include/Private.php';

$weblog = WeblogTemplate::load(Request::getId());

if ($weblog) {	
	$values = array(
		'id' => $weblog->getId(),
		'title' => $weblog->getTitle(),
		'blueprint' => $weblog->getPageBlueprintId(),
		'groups' => $weblog->getGroupIds()
	);

	Response::sendObject($values);
} else {
	Response::notFound();
}
?>