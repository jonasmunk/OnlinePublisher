<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

Response::sendObject([
    'title' => ['en' => 'Select file','da' => 'Vælg fil' ],
    'list' => ['url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/FilesList.php'],
	'selection' => ['value' => 'all', 'parameter' => 'group', 'url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/FilesSelection.php'],
	'search' => ['parameter' => 'query']
])
?>