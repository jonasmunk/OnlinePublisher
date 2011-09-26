<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/Request.php';

$subset = Request::getString('subset');
$group = Request::getInt('group',null);
$text = Request::getEncodedString('text');

InternalSession::setToolSessionVar('images','group',$group);

$query = Query::after('image')->withText($text)->withWindowSize(500);
if ($subset=='unused') {
	$query->withCustom('unused',true);
}
if ($subset=='latest') {
	$query->withCustom('createdAfter',DateUtils::addDays(mktime(),-1));
}
if ($group===-1) {
	$query->withCustom('nogroup',true);
} else if ($group) {
	$query->withCustom('group',$group);
}
$list = $query->search()->getList();

In2iGui::sendObject($list);
?>