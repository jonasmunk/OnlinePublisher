<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Hierarchy.php';

require_once 'Functions.php';

$id = requestGetNumber('id',0);
$dir = requestGetNumber('dir',0);
$return = requestGetText('return');
$dontUpdateHierarchy = requestGetBoolean('dontUpdateHierarchy');

Hierarchy::moveItem($id,$dir);
if (!$dontUpdateHierarchy) {
	setToolSessionVar('pages','updateHier',true);
}
redirect($return);
?>