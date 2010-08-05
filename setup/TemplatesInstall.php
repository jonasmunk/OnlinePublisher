<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';

$unique = requestGetText('unique');

$sql="insert into template (`unique`) values (".Database::text($unique).")";
Database::insert($sql);

header("Location: Templates.php");
?>