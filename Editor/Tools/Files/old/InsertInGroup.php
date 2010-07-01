<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$files = requestPostArray('file');


for ($i=0;$i<count($files);$i++) {
	$sql="insert into filegroup_file (file_id, filegroup_id)".
	" values (".$files[$i].",".$id.")";
	Database::insert($sql);
}

setToolSessionVar('files','updateHierarchy',true);

redirect('Group.php');
?>