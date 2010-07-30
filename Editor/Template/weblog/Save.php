<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$data = Request::getUnicodeObject('data');
	
if ($weblog = Weblog::load($data->id)) {
	
	$weblog->setTitle($data->title);
	$weblog->setPageBlueprintId($data->blueprint);
	$weblog->setGroupIds($data->groups);

	$weblog->save();
}
?>