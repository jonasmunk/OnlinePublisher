<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getToolSessionVar('files','group');
$files = requestPostArray('file');
$single = requestGetNumber('id',0);
if ($single>0) $files=array($single);


for ($i=0;$i<count($files);$i++) {
	$sql="delete from filegroup_file where file_id=".$files[$i].
	" and filegroup_id=".$id;
	Database::delete($sql);
}

setToolSessionVar('files','updateHierarchy',true);
if ($single>0) {
	redirect('Group.php');
}
else {
	redirect('FileList.php');
}
?>