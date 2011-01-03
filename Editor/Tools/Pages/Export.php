<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Parts.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',-1);
$debug = Request::getBoolean('debug');
$page = Page::load($id);

header('Content-Type: text/xml; charset: ISO-8859-1;');
if (!$debug) {
	header('Content-Disposition: attachment; filename="'.$page->getTitle().'.op.xml"');
}
echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
echo $page->export();
?>