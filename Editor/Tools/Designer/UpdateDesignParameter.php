<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Design.php';

$designId = requestPostNumber('designId');
$key = requestPostText('key');
$value = requestPostText('value');
$type = requestPostText('type');

$design = Design::load($designId);
$design->setParameter($key,$type,$value);
$design->update();
$design->publish();

redirect('EditDesignParameters.php?id='.$designId);
?>