<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Hierarchy.php';

$id=requestGetNumber('id',0);

$hier = Hierarchy::load($id);
$hier->delete();

redirect('Hierarchies.php');
?>