<?
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';
?>
<HTML>
<BODY bgcolor="#FFFFFF">
<table border="0" width="100%" height="100%"><tr>
<td align="center">
<?php
//include charts.php to access the InsertChart function
include "../../Libraries/charts/charts.php";
echo InsertChart ( "../../Libraries/charts/charts.swf", "../../Libraries/charts/charts_library", requestGetText("data"), 600, 400, "ffffff" );

?>
</td></tr></table>
</BODY>
</HTML>