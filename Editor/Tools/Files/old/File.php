<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';

if (requestGetExists('group')) {
	$group = requestGetNumber('group');
	setToolSessionVar('files','group',$group);
	if ($group>0) {
		setToolSessionVar('files','baseWindow','Group.php');
	}
	else {
		setToolSessionVar('files','baseWindow','Library.php');
	}
}
$id = requestGetNumber('id',0);

$view = getToolSessionVar('files','fileView');
if ($view=='view') {
	$location = 'FileView.php';
}
else if ($view=='properties') {
	$location = 'FileProperties.php';
}
else {
	$location = 'FileInfo.php';
}
redirect($location.'?id='.$id);
?>