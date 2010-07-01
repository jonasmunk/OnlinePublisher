<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestPostNumber('id',0);
$type = requestPostText('type');
$language = requestPostText('language');
$page = requestPostNumber('page',0);

$sql="update specialpage set".
" `type`=".sqlText($type).
",language=".sqlText($language).
",page_id=".$page.
" where id=".$id;

Database::update($sql);

redirect('SpecialPages.php');
?>