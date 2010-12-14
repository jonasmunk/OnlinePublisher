<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once 'Functions.php';

$id = requestPostNumber('id');
$pageId=InternalSession::getPageId();
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

	$sql="update link set source_text=".Database::text($text).",target_type=".Database::text($targetType).",target_value=".Database::text($targetValue).",target_id=".$targetId.",target=".Database::text($target).",alternative=".Database::text($alternative)." where id=".$id;
	Database::update($sql);

	$sql="update page set changed=now() where id=".$pageId;
	Database::update($sql);

}
redirect('Editor.php');
?>