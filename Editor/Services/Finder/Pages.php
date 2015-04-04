<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

Response::sendObject([
    'title' => ['en' => 'Select page','da' => 'Vælg side' ],
    'list' => ['url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/PagesList.php'],
	'selection' => ['value' => 'all', 'parameter' => 'group', 'url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/PagesSelection.php'],
	'search' => ['parameter' => 'query']
])
?>