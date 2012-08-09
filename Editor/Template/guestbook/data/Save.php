<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Include/Private.php';

$id = Request::getId();
$title = Request::getString('title');
$text = Request::getString('text');

$sql="update guestbook set text=".Database::text($text).",title=".Database::text($title)." where page_id=".Database::int($id);
Database::update($sql);

PageService::markChanged($id);
?>