<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id',0);

$hierarchyId = Hierarchy::deleteItem($id);

InternalSession::setToolSessionVar('pages','updateHier',true);

if (Request::exists('return')) {
    Response::redirect(Request::getString('return'));
} else {
    Response::redirect('EditHierarchy.php?id='.$hierarchyId);    
}
?>