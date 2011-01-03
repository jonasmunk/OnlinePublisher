<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$name = Request::getString('name');
$language = Request::getString('language');
$return = Request::getString('return');

$hier = Hierarchy::load($id);
$hier->setName($name);
$hier->setLanguage($language);
$hier->update();

InternalSession::setToolSessionVar('pages','updateHier',true);

Response::redirect($return);
?>