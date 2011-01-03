<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);
$type = Request::getString('type');
$language = Request::getString('language');
$page = Request::getInt('page',0);

$sql="update specialpage set".
" `type`=".Database::text($type).
",language=".Database::text($language).
",page_id=".$page.
" where id=".$id;

Database::update($sql);

redirect('SpecialPages.php');
?>