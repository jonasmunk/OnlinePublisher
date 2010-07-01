<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
?>
<html>
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