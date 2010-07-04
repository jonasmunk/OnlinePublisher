<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Page.php';

$blueprint = Request::getPostInt('blueprint');
$title = Request::getPostString('title');
$groups = Request::getPostArray('group');
$id = getPageId();

$sql = "update weblog set pageblueprint_id=".Database::int($blueprint).",title=".Database::text($title)." where page_id=".$id;
Database::update($sql);

$sql = "delete from weblog_webloggroup where page_id=".$id;
Database::delete($sql);

foreach ($groups as $group) {
	$sql = "insert into weblog_webloggroup (page_id,webloggroup_id) values (".Database::int($id).",".Database::int($group).")";
	Database::insert($sql);
}

Page::markChanged($id);

redirect('Editor.php');
?>