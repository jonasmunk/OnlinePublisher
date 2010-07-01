<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/FileSystemUtil.php';
require_once '../../Include/Images.php';

require_once 'ImagesController.php';

$close = ImagesController::getBaseWindow();
$dir = $basePath.'dropbox/';
$files = FileSystemUtil::listFilesRecurse($dir);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Nyt billede" icon="Element/Image">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Egen computer" icon="Tool/Hardware" link="NewImage.php"/>'.
'<space/>'.
'<tool title="Komprimeret arkiv" icon="File/zip" link="NewImageCompressed.php"/>'.
'<space/>'.
'<tool title="Dueslag" icon="Basic/Inbox" selected="true"/>'.
'<space/>'.
'<tool title="Internettet" icon="Basic/Internet" link="NewImageInternet.php"/>'.
'</toolbar>'.
'<content padding="10" background="true">'.
'<area xmlns="uri:Area" width="100%">'.
'<content padding="5">'.
'<text xmlns="uri:Text" align="center" top="5" bottom="10">'.
'<strong>Billeder fra drop-boksen</strong><break/>'.
'<small>Vælg billeder fra drop-boksen som du vil tilføje til biblioteket...</small>'.
'</text>'.
'<form xmlns="uri:Form" action="CreateImageDropbox.php" method="post" name="Formula" enctype="multipart/form-data">'.
'<list width="100%" object="List" margin="3" xmlns="uri:List">'.
'<content>'.
'<headergroup>'.
'<header width="1%" align="center" checker="true"/>'.
'<header title="Filnavn" width="99%"/>'.
'</headergroup>';
foreach ($files as $file) {
	if (isSupportedImageFile($file)) {
		$gui.='<row>'.
		'<cell><checkbox name="file[]" value="'.encodeXML($file).'"/></cell>'.
		'<cell><icon icon="File/png"/><text>'.encodeXML(substr($file,strlen($dir))).'</text></cell>'.
		'</row>';
	}
}
$gui.='</content></list>'.

'<group size="Large">'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Importer" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</area>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Text","Area","List");
writeGui($xwg_skin,$elements,$gui);
?>