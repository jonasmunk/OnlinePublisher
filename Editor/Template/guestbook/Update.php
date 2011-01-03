<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';

$id = InternalSession::getPageId();
$title = Request::getString('title');
$text = Request::getString('text');

$sql="update guestbook set".
" title=".Database::text($title).
",text=".Database::text($text).
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$id;
Database::update($sql);

redirect('Text.php');

?>