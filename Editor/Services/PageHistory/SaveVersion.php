<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/PageService.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';

$id = Request::getId();
PageService::saveSnapshot($id);

Response::redirect('index.php');
?>