<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Image.php';

$images = Image::search();

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<objects>';
foreach ($images as $image) {
	echo '<entity image="../../../util/images/?id='.$image->getId().'&amp;maxwidth=32&amp;maxheight=32&amp;format=jpg&amp;timestamp='.$image->getUpdated().'" title="'.encodeXML(shortenString($image->getTitle(),30)).'" value="'.$image->getId().'"/>';
}
echo '</objects>'
?>