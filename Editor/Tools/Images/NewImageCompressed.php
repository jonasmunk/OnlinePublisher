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
'<tool title="Egen computer" icon="Tool/Hardware" link="NewImage.php"/>'.
'<space/>'.
'<tool title="Komprimeret arkiv" icon="File/zip" selected="true"/>'.
'<space/>'.
'<tool title="Dueslag" icon="Basic/Inbox" link="NewImageDropbox.php"/>'.
'<space/>'.
'<tool title="Internettet" icon="Basic/Internet" link="NewImageInternet.php"/>'.
'</toolbar>'.
'<content padding="10" background="true">'.
'<area width="100%" xmlns="uri:Area">'.
'<content padding="5">'.
'<text xmlns="uri:Text" align="center" top="5" bottom="10">'.
'<strong>Billeder fra komprimeret arkiv</strong><break/>'.
'<small>Her kan du vælge et komprimeret arkiv fra din computer med flere billeder...</small>'.
'</text>'.
'<form xmlns="uri:Form" action="CreateImageCompressed.php?upload=true" method="post" name="Formula" focus="title" enctype="multipart/form-data">'.
'<group size="Large">'.
'<file badge="Komprimeret arkiv:" name="file"/>'.
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