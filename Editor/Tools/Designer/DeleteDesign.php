<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Design.php';

$id = requestGetNumber('id');

$design = Design::load($id);
$design->remove();

redirect('index.php');
?>