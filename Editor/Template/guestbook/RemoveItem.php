<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = requestGetNumber('id');

$sql="delete from guestbook_item where id=".$id;
Database::delete($sql);

redirect('Items.php');

?>