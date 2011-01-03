<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once 'PagesController.php';

$id = Request::getInt('id');

redirect('HierarchyItemFrame.php?id='.$id);
?>