<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';

$id=Request::getInt('id',0);

$sql='delete from specialpage where id='.$id;
Database::delete($sql);

Response::redirect('SpecialPages.php');
?>