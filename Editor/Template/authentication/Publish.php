<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Publishing.php';

$id = getPageId();

publishPage($id);

redirect('Toolbar.php');
?>