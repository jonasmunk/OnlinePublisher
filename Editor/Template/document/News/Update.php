<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../Functions.php';

$pageId = getPageId();
$id = getDocumentSection();
$left = requestPostText('left');
$right = requestPostText('right');
$top = requestPostText('top');
$bottom = requestPostText('bottom');
$align = requestPostText('align');
$groups = requestPostText('groups');
$mode = requestPostText('mode');
$news = requestPostNumber('news',0);
$maxitems = requestPostNumber('maxitems',0);
$sortby = requestPostText('sortby');
$sortdir = requestPostText('sortdir');
$timetype = requestPostText('timetype');
$timecount = requestPostNumber('timecount',1);
$title = requestPostText('title');


$sql="update document_news set".
" align=".Database::text($align).
",title=".Database::text($title).
",mode=".Database::text($mode).
",sortby=".Database::text($sortby).
",sortdir=".Database::text($sortdir).
",timetype=".Database::text($timetype).
",timecount=".$timecount.
",maxitems=".$maxitems.
",news_id=".$news.
" where section_id=".$id;
Database::update($sql);

$sql="delete from document_news_newsgroup where section_id=".$id;
Database::delete($sql);

if ($mode=='groups') {
	$groups = explode(",",$groups);
	foreach ($groups as $group) {
		if (is_numeric($group)) {
			$sql="insert into document_news_newsgroup (section_id,page_id,newsgroup_id) values (".$id.",".$pageId.",".$group.")";
			Database::insert($sql);
		}
	}
}
$sql="update document_section set".
" `left`=".Database::text($left).
",`right`=".Database::text($right).
",`top`=".Database::text($top).
",`bottom`=".Database::text($bottom).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


redirect('../Editor.php?section=');
?>