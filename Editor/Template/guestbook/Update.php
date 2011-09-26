<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';

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

Response::redirect('Text.php');

?>