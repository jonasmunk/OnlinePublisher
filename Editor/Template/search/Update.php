<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$text = requestPostText('text');
$buttontitle = requestPostText('buttontitle');

$sql="update search set".
" title=".sqlText($title).
",`text`=".sqlText($text).
",buttontitle=".sqlText($buttontitle).
" where page_id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$id;
Database::update($sql);


redirect('Properties.php');
?>