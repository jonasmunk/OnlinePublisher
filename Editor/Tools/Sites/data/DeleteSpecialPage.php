<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Interface/In2iGui.php';
require_once '../../../Classes/Model/SpecialPage.php';

$id = Request::getInt('id');
if ($obj = SpecialPage::load($id)) {
	$obj->remove();
} else {
	Response::badRequest();
}
?>