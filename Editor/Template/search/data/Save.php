<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

$obj = Search::load($data->id);
if ($obj) {
	$obj->setTitle($data->title);
	$obj->setText($data->text);
	foreach (Search::$TYPES as $type => $label) {
		foreach (Search::$PARTS as $part => $kind) {
			$method = $type.ucfirst($part);
			$obj->$method = $data->$method;
		}
	}
	$obj->save();
} else {
	Response::notFound();
}
?>