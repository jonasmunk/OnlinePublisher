<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

Response::sendObject([
    'title' => ['en' => 'Select image','da' => 'Vælg billede' ],
    'list' => ['url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ImagesList.php'],
    'gallery' => ['url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ImagesGallery.php'],
    'selection' => ['value' => 'all', 'parameter' => 'group', 'url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ImagesSelection.php'],
	'search' => ['parameter' => 'query'],
    'upload' => [
        'url' => ConfigurationService::getBaseUrl() . 'Editor/Services/Finder/ImagesUpload.php',
        'placeholder' => [
            'title' => ['en' => 'Upload image', 'da' => 'Overfør billede'],
            'text' => ['en' => 'The file can at most be '.$maxUploadSize.' large', 'da' => 'Filen kan højest være '.$maxUploadSize.' stor']
        ]
    ]
])
?>