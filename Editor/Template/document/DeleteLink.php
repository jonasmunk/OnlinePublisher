<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id=requestGetNumber('id',0);

$sql="delete from link where id=".$id;
Database::delete($sql);

redirect('ListOfLinks.php');
?>