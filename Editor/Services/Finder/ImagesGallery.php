<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.ImageChooser
 */
require_once '../../Include/Private.php';

$text = Request::getString('text');
$query = Request::getString('query'); // TODO: Legacy support
$subset = Request::getString('subset');
$group = Request::getInt('group',null);

if (Strings::isNotBlank($query)) {
    $text = $query;
}

$query = Query::after('image')->withText($text)->withWindowSize(500);

if ($subset=='unused') {
	$query->withCustom('unused',true);
}
if ($subset=='nogroup') {
	$query->withCustom('nogroup',true);
}
if ($subset=='latest') {
	$query->withCustom('createdAfter',Dates::addDays(mktime(),-1));
}
if ($group===-1) {
	$query->withCustom('nogroup',true);
} else if ($group) {
	$query->withCustom('group',$group);
}

$objects = $query->get();

$out = [];

foreach ($objects as $object) {
	$out[] = [
        'id' => $object->getId(),
        'width' => $object->getWidth(),
        'height' => $object->getHeight()
    ];
}

Response::sendObject($out);
?>