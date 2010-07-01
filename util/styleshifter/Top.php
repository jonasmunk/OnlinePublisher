<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Util
 */
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Functions.php';
require_once '../../Editor/Classes/Design.php';
?>
<html>
<head>
	<style>
	body {
		margin: 0px;
		padding: 3px;
		font: 8pt Verdana;
		text-transform: capitalize;
		background-color: #eee;
		border-bottom: solid 1px #aaa;
	}
	a {
		color: #000;
		text-decoration: none;
	}
	a:hover {
		text-decoration: underline;
	}
	</style>
</head>
<body>
<?php
$styles = Design::getAvailableDesigns();
$first = true;
foreach ($styles as $style) {
	if (!$first) echo ' &middot; ';
	echo '<a href="../../?designsession='.$style.'" target="Page">'.$style.'</a>';
	$first=false;
}
?>
</body>
</html>