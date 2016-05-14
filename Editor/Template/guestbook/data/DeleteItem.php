<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Include/Private.php';

$sql="delete from guestbook_item where id=".Database::int(Request::getId());
Database::delete($sql);
?>