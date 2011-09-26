<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id',0);
$return = Request::getString('return');

PublishingService::publishFrame($id);

if ($return=='links') {
	Response::redirect('EditFrameLinks.php?id='.$id.'&position='.Request::getString('position'));
}
else if ($return=='search') {
	Response::redirect('EditFrameSearch.php?id='.$id);
}
else if ($return=='news') {
	Response::redirect('FrameNews.php?id='.$id);
}
else if ($return=='userstatus') {
	Response::redirect('EditFrameUserstatus.php?id='.$id);
}
else {
	Response::redirect('EditFrame.php?id='.$id);
}
?>