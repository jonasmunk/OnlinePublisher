<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$word = Request::getUnicodeString('word');

$phrase = new Testphrase();
$phrase->setTitle($word);
$phrase->save();
$phrase->publish();
?>