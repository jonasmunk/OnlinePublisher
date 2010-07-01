<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Hierarchy.php';

$name=requestPostText('name');
$language=requestPostText('language');

$hier = new Hierarchy();
$hier->setName($name);
$hier->setLanguage($language);
$hier->create();

redirect('Hierarchies.php');
?>