<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ProductListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = getProductListingId();
$text = Request::getString('text');
$title = Request::getString('title');

$sql="update productlisting set".
" title=".Database::text($title).
",`text`=".Database::text($text).
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$id;
Database::update($sql);

redirect('Text.php');
?>