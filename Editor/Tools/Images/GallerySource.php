<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';

$subset = Request::getString('subset');
$group = Request::getInt('group',null);
$text = Request::getEncodedString('text');


$query = Query::after('image')->withText($text);
if ($subset=='unused') {
	$query->withCustom('unused',true);
}
if ($group===-1) {
	$query->withCustom('nogroup',true);
} else if ($group) {
	$query->withCustom('group',$group);
}
$list = $query->search()->getList();

In2iGui::sendObject($list);
?>