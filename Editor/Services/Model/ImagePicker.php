<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../Include/Private.php';

$queryString = Request::getString('query');

header('Content-Type: text/xml;');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<images>';

$images = Image::search();
foreach ($images as $image) {
	echo '<image id="'.$image->getId().'"/>';
}

echo '</images>';
?>