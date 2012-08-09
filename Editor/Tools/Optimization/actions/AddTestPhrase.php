<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$word = Request::getString('word');

$phrase = new Testphrase();
$phrase->setTitle($word);
$phrase->save();
$phrase->publish();
?>