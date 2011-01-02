<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';

require_once 'Functions.php';

$id = Request::getInt('id',0);
$dir = Request::getInt('dir',0);
$return = Request::getString('return');
$dontUpdateHierarchy = Request::getBoolean('dontUpdateHierarchy');

Hierarchy::moveItem($id,$dir);
if (!$dontUpdateHierarchy) {
	InternalSession::setToolSessionVar('pages','updateHier',true);
}
redirect($return);
?>