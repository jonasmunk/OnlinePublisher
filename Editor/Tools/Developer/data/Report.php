<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

header('Content-Type: text/html; charset=iso-8859-1');
?>
<!DOCTYPE html>
<html>
<head>
<meta xmlns="http://www.w3.org/1999/xhtml" http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../../../Resources/report.css" media="screen" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php
echo ReportService::generateReport();
?>
<textarea>
<?php
echo ReportService::generateFullReport();
?>
</textarea>
</body>
</html>