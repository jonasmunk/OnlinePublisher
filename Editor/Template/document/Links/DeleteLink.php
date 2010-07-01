<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Link.php';

$id=Request::getInt('id');

if ($link = Link::load($id)) {
	$link->remove();
}
?>