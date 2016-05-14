<?php
require_once('../../Editor/Include/Public.php');

//sleep(1);

$id = Request::getInt('id');

$usage = Waterusage::load($id);
if (!$usage) {
	Response::sendNotFound();
} else {
	$usage->remove();
}?>