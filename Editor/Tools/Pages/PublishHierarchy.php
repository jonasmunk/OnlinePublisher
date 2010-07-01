<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Hierarchy.php';

$id = requestGetNumber('id');

$hier = Hierarchy::load($id);
$hier->publish();

redirect('EditHierarchy.php?id='.$id);
?>