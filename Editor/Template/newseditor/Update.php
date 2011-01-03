<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Newseditor
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/InternalSession.php';

$id = InternalSession::getPageId();
Page::markChanged($id);

Response::redirect('Editor.php');
?>