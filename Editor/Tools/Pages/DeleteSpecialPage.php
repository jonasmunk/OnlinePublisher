<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id=requestGetNumber('id',0);

$sql='delete from specialpage where id='.$id;
Database::delete($sql);

redirect('SpecialPages.php');
?>