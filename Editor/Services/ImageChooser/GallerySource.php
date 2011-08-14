<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.ImageChooser
 */
require_once '../../Include/Private.php';

$text = Request::getString('text');
$subset = Request::getString('subset');
$group = Request::getInt('group',null);

$query = Query::after('image')->withText($text)->withWindowSize(500);

if ($subset=='unused') {
	$query->withCustom('unused',true);
}
if ($subset=='nogroup') {
	$query->withCustom('nogroup',true);
}
if ($subset=='latest') {
	$query->withCustom('createdAfter',DateUtils::addDays(mktime(),-1));
}
if ($group===-1) {
	$query->withCustom('nogroup',true);
} else if ($group) {
	$query->withCustom('group',$group);
}

$objects = $query->get();

$writer = new ItemsWriter();

$writer->startItems();

foreach ($objects as $object) {
	$writer->item(array(
		'title' => $object->getTitle(),
		'value' => $object->getId(),
		'icon' => $object->getIn2iGuiIcon(),
		'kind' => $type
	));
}

$writer->endItems();
?>