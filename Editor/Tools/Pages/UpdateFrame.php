<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);
$name = Request::getString('name');
$title = Request::getString('title');
$bottomtext = Request::getString('bottomtext');
$hierarchy = Request::getInt('hierarchy',0);

$sql="update frame set".
" name=".Database::text($name).
",title=".Database::text($title).
",bottomtext=".Database::text($bottomtext).
",hierarchy_id=".$hierarchy.
" where id=".$id;

Database::update($sql);

Response::redirect('EditFrame.php?id='.$id);
?>