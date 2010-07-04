<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.PersonListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getPersonListingId();
$text = requestPostText('text');
$title = requestPostText('title');

$sql="update personlisting set".
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