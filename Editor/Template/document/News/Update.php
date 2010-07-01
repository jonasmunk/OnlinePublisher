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
" align=".sqlText($align).
",title=".sqlText($title).
",mode=".sqlText($mode).
",sortby=".sqlText($sortby).
",sortdir=".sqlText($sortdir).
",timetype=".sqlText($timetype).
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
" `left`=".sqlText($left).
",`right`=".sqlText($right).
",`top`=".sqlText($top).
",`bottom`=".sqlText($bottom).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


redirect('../Editor.php?section=');
?>