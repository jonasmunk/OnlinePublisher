<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

	</head>
	<body style="margin: 0px; background: #F3F5F7; overflow-y: hidden;">
		<table border="0" cellpadding="0" cellspacing="0" align="" width="100%" height="100%">
			<tr><td height="100%" style="padding: 5px 5px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
					<tr><td height="" colspan="3" valign="top" align="left">
						<iframe ALLOWTRANSPARENCY="true" id="id491604" style="width: 100%; height: 100%; border: 1px solid #ccc; background-color: white;" frameborder="0" src="viewer/" onload="this.style.backgroundImage=''" name="Preview"> </iframe>
					</td></tr>
				</table>
			</td></tr>
		</table>
	</body>
</html>

<?php
exit;
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%" margin="5">'.
'<content>'.
'<iframe xmlns="uri:Frame" source="viewer/" name="Preview"/>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame");
writeGui($xwg_skin,$elements,$gui);
?>