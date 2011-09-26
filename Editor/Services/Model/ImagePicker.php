<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Objects/Image.php';
require_once '../../Classes/Core/Request.php';

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