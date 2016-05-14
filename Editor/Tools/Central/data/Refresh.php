<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../../Include/Private.php';

$objects = Query::after('remotepublisher')->orderBy('title')->get();
foreach ($objects as $site) {
	$data = RemoteDataService::getRemoteData($site->getUrl().'services/info/json/',0);
}

