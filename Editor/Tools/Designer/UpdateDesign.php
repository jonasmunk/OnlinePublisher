<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Design.php';

$id=requestPostNumber('id');
$title=requestPostText('title');
$unique=requestPostText('unique');

$design = Design::load($id);
$design->setTitle($title);
$design->setUnique($unique);
$design->update();
$design->publish();

redirect('index.php');
?>