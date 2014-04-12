<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

Response::sendObject([
    'title' => ['en' => 'Select image','da' => 'Vælg billede' ],
    'list' => ['url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ImagesList.php'],
    'selection' => ['value' => 'all', 'parameter' => 'group', 'url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ImagesSelection.php'],
	'search' => ['parameter' => 'query']
])
?>