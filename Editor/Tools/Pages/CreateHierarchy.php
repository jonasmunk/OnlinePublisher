<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Core/Request.php';

$name=Request::getString('name');
$language=Request::getString('language');

$hier = new Hierarchy();
$hier->setName($name);
$hier->setLanguage($language);
$hier->create();

Response::redirect('Hierarchies.php');
?>