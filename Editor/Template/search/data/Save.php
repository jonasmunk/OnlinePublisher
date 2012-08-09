<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$obj = SearchTemplate::load($data->id);
if ($obj) {
	$obj->setTitle($data->title);
	$obj->setText($data->text);
	foreach (SearchTemplate::$TYPES as $type => $label) {
		foreach (SearchTemplate::$PARTS as $part => $kind) {
			$method = $type.ucfirst($part);
			$obj->$method = $data->$method;
		}
	}
	$obj->save();
} else {
	Response::notFound();
}
?>