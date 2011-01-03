<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

$type = Request::getString('type');
$language = Request::getString('language');
$page = Request::getInt('page',0);


$sql="insert into specialpage (`type`,language,page_id) values (".
Database::text($type).",".Database::text($language).",".$page.")";

Database::insert($sql);

redirect('SpecialPages.php');
?>