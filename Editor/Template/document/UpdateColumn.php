<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getDocumentColumn();
$width=requestPostText('width');


$sql="update document_column set".
" width=".Database::text($width).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".getPageId();
Database::update($sql);

setDocumentColumn(-1);
redirect('Editor.php');
?>