<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

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