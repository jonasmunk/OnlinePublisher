<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

$_SESSION['core.debug.simulateLatency']=$data->simulateLatency;
$_SESSION['core.debug.logDatabaseQueries']=$data->logDatabaseQueries;
?>