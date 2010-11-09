<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once 'ImagesController.php';

$id=requestGetNumber('id',0);

$view = ImagesController::getImageView();
if (requestGetExists('group')) {
	$group = requestGetNumber('group');
	ImagesController::setGroupId($group);
	if ($group>0) {
		ImagesController::setViewType('group');
	}
	else {
		ImagesController::setViewType('all');
	}
}

if ($view=='properties') {
	redirect('ImageProperties.php?id='.$id);
}
else if ($view=='view') {
	redirect('ImageView.php?id='.$id);
}
else if ($view=='info') {
	redirect('ImageInfo.php?id='.$id);
}
?>