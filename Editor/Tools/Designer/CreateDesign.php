<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Design.php';

$unique=requestPostText('unique');
$title=requestPostText('title');

$design = new Design();
$design->setTitle($title);
$design->setUnique($unique);
$design->create();

redirect('index.php');
?>