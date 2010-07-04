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

$pageId=getPageId();
$text=requestPostText('text');
$targetType=requestPostText('targetType');
$target=requestPostText('target');
$targetId=0;
$targetValue='';
if ($targetType=='page') {
	$targetId=requestPostNumber('page',0);
}
else if ($targetType=='file') {
	$targetId=requestPostNumber('file',0);
}
else if ($targetType=='url') {
	$targetValue=requestPostText('url');
}
else if ($targetType=='email') {
	$targetValue=requestPostText('email');
}
$alternative=requestPostText('alternative');
if (strlen($text)>0) {

	$sql="insert into link (page_id,source_type,source_text,target_type,target_value,target_id,target,alternative) values (".$pageId.",'text',".Database::text($text).",'".$targetType."',".Database::text($targetValue).",".$targetId.",".Database::text($target).",".Database::text($alternative).")";
	Database::insert($sql);

	$sql="update page set changed=now() where id=".$pageId;
	Database::update($sql);

}
redirect('Editor.php');
?>