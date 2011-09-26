<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id',0);
$title = Request::getString('title');
$text = Request::getString('text');
$buttontitle = Request::getString('buttontitle');

$sql="update search set".
" title=".Database::text($title).
",`text`=".Database::text($text).
",buttontitle=".Database::text($buttontitle).
" where page_id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$id;
Database::update($sql);

Response::redirect('Properties.php');
?>