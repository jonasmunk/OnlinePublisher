<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Request.php';
require_once '../../../../Classes/In2iGui.php';
require_once '../../../../Classes/Services/PartService.php';

$id = Request::getInt('id');
$type = Request::getString('type');

$part = PartService::load($type,$id);
$part->toUnicode();
In2iGui::sendObject($part);
?>