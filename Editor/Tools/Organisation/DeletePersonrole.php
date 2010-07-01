<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Personrole.php';
require_once 'Functions.php';

$id = requestGetNumber('id',0);
$personRole = PersonRole::load($id);
$personRole->remove();

setUpdateHierarchy(true);
redirect('Roles.php');
?>