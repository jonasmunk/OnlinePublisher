<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';

$id=Request::getInt('id');
$page=Request::getInt('page');


$sql="delete from page_translation where id=".$id;
Database::delete($sql);


redirect('EditPageTranslations.php?id='.$page);
?>