<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Hierarchy.php';

$id = requestGetNumber('id',0);

$hierarchyId = Hierarchy::deleteItem($id);

InternalSession::setToolSessionVar('pages','updateHier',true);

if (requestGetExists('return')) {
    redirect(requestGetText('return'));
} else {
    redirect('EditHierarchy.php?id='.$hierarchyId);    
}
?>