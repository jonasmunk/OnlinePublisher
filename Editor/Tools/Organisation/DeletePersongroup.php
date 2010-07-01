<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Persongroup.php';
require_once 'Functions.php';

$id = requestGetNumber('id',0);

$personGroup = PersonGroup::load($id);
$personGroup->remove();

setUpdateHierarchy(true);
redirect('Library.php');
?>