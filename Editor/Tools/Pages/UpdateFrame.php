<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestPostNumber('id',0);
$name = requestPostText('name');
$title = requestPostText('title');
$bottomtext = requestPostText('bottomtext');
$hierarchy = requestPostNumber('hierarchy',0);

$sql="update frame set".
" name=".sqlText($name).
",title=".sqlText($title).
",bottomtext=".sqlText($bottomtext).
",hierarchy_id=".$hierarchy.
" where id=".$id;

Database::update($sql);

redirect('EditFrame.php?id='.$id);
?>