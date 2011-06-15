<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Model/SpecialPage.php';

$id = Request::getInt('id');
$obj = SpecialPage::load($id);

In2iGui::sendUnicodeObject($obj);
?>