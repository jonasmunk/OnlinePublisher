<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';

$unique = requestGetText('unique');

$sql="insert into tool (`unique`) values (".sqlText($unique).")";
Database::insert($sql);

header("Location: Tools.php");
?>