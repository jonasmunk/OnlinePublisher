<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');

Response::sendObject([
    'title' => ['en' => 'Select object','da' => 'Vælg objekt' ],
    'list' => ['url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ObjectsList.php?type='.$type],
    'selection' => ['value' => 'all', 'parameter' => 'group', 'url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ObjectsSelection.php?type='.$type],
	'search' => ['parameter' => 'query']
])
?>