<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';

$id=Request::getInt('id');
$page=Request::getInt('page');


$sql="delete from page_translation where id=".$id;
Database::delete($sql);


Response::redirect('EditPageTranslations.php?id='.$page);
?>