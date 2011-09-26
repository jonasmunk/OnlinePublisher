<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');

$sql="delete from guestbook_item where id=".$id;
Database::delete($sql);

Response::redirect('Items.php');

?>