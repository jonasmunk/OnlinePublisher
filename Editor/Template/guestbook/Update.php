<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = getPageId();
$title = requestPostText('title');
$text = requestPostText('text');

$sql="update guestbook set".
" title=".sqlText($title).
",text=".sqlText($text).
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$id;
Database::update($sql);

redirect('Text.php');

?>