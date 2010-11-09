<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

require_once 'ImagesController.php';

$close = ImagesController::getBaseWindow();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Nyt billede" icon="Element/Image">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Egen computer" icon="Tool/Hardware" selected="true"/>'.
'<space/>'.
'<tool title="Komprimeret arkiv" icon="File/zip" link="NewImageCompressed.php"/>'.
'<space/>'.
'<tool title="Dueslag" icon="Basic/Inbox" link="NewImageDropbox.php"/>'.
'<space/>'.
'<tool title="Internettet" icon="Basic/Internet" link="NewImageInternet.php"/>'.
'</toolbar>'.
'<content padding="10" background="true">'.
'<area xmlns="uri:Area" width="100%">'.
'<content padding="5">'.
'<text xmlns="uri:Text" align="center" top="5" bottom="10">'.
'<strong>Billede fra egen computer</strong><break/>'.
'<small>Vælg en billedfil på din lokale computer der skal tilføjes til biblioteket...</small>'.
'</text>'.
'<form xmlns="uri:Form" action="CreateImage.php" method="post" name="Formula" focus="title" enctype="multipart/form-data">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<file badge="Billede:" name="file"/>'.
'<space/>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Upload" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</area>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Text","Area");
writeGui($xwg_skin,$elements,$gui);
?>