<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Filegroup.php';
require_once 'Functions.php';

$id = requestGetNumber('id',0);

$group = FileGroup::load($id);
$group->remove();

setToolSessionVar('files','updateHierarchy',true);
redirect('Library.php');
?>