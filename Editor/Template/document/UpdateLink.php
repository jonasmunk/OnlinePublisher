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

$id = requestPostNumber('id');
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

	$sql="update link set source_text=".sqlText($text).",target_type=".sqlText($targetType).",target_value=".sqlText($targetValue).",target_id=".$targetId.",target=".sqlText($target).",alternative=".sqlText($alternative)." where id=".$id;
	Database::update($sql);

	$sql="update page set changed=now() where id=".$pageId;
	Database::update($sql);

}
redirect('Editor.php');
?>