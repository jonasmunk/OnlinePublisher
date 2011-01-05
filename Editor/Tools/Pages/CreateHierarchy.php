<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';

$name=Request::getString('name');
$language=Request::getString('language');

$hier = new Hierarchy();
$hier->setName($name);
$hier->setLanguage($language);
$hier->create();

Response::redirect('Hierarchies.php');
?>