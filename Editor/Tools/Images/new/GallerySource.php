<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Image.php';
require_once '../../../Classes/Services/FileService.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Log.php';

$group = Request::getInt('group',null);
$text = Request::getEncodedString('text');


$list = Query::after('image')->withText($text)->withCustom('group',$group)->search();

In2iGui::sendObject($list['result']);
?>