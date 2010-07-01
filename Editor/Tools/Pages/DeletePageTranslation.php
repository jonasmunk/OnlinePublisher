<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id=requestGetNumber('id');
$page=requestGetNumber('page');


$sql="delete from page_translation where id=".$id;
Database::delete($sql);


redirect('EditPageTranslations.php?id='.$page);
?>