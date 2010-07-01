<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Milestone.php';

$id = requestGetNumber('id');

$milestone = Milestone::load($id);
$milestone->remove();


redirect('Milestones.php');
?>