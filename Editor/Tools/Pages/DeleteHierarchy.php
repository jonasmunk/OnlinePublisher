<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Core/Request.php';

$id=Request::getInt('id',0);

$hier = Hierarchy::load($id);
$hier->delete();

Response::redirect('Hierarchies.php');
?>