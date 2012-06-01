<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');
	
if ($weblog = WeblogTemplate::load($data->id)) {
	
	$weblog->setTitle($data->title);
	$weblog->setPageBlueprintId($data->blueprint);
	$weblog->setGroupIds($data->groups);

	$weblog->save();
}
?>