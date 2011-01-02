<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Include/Functions.php';

$id = Request::getInt('id',0);
$enabled = Request::getCheckbox('enabled');
$page = Request::getInt('page',0);

$sql="update frame set".
" userstatusenabled=".Database::boolean($enabled).
",userstatuspage_id=".$page.
",changed=now()".
" where id=".$id;

Database::update($sql);

redirect('EditFrameUserstatus.php?id='.$id);
?>