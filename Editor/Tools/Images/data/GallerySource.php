<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$subset = Request::getString('subset');
$group = Request::getInt('group',null);
$text = Request::getString('text');

InternalSession::setToolSessionVar('images','group',$group);

$query = Query::after('image')->withText($text)->withWindowSize(500);
if ($subset=='unused') {
	$query->withCustom('unused',true);
}
if ($subset=='latest') {
	$query->withCustom('createdAfter',Dates::addDays(mktime(),-1));
}
if ($group===-1) {
	$query->withCustom('nogroup',true);
  $query->orderBy('title');
} else if ($group) {
	$query->withCustom('group',$group);
  $query->orderBy('position')->orderBy('title');
} else {
  $query->orderBy('title');
}
$list = $query->search()->getList();

Response::sendObject($list);
?>