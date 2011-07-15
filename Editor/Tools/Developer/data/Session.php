<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

header('Content-Type: text/html; charset=iso-8859-1');
?>
<html>
<head>
<meta xmlns="http://www.w3.org/1999/xhtml" http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<pre>
<?
$s = $_SESSION;
ksort($s);
print_r($s);
?>
</pre>
</body>
</html>