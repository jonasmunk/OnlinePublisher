<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Include/Session.php';
require_once 'PagesController.php';

$info = PagesController::getNewPageInfo();
if (requestGetExists('hierarchy') && requestGetExists('parent')) {
	$info['hierarchy']=requestGetNumber('hierarchy');
	$info['hierarchyParent']=requestGetNumber('parent');
}
else if (requestGetExists('frame')){
	$info['frame']=requestGetNumber('frame');
}
PagesController::setNewPageInfo($info);

$close = getToolSessionVar('pages','rightFrame');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Ny side" icon="Tool/Assistant">'.
'<close link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<layout xmlns="uri:Layout" width="100%" height="100%">'.
'<row><cell valign="top">'.
'<group xmlns="uri:Icon" size="1" titles="right" spacing="6" wrapping="false">'.
'<row><icon icon="Element/Template" title="Vælg skabelon" style="Disabled"/></row>'.
'<row><icon icon="Basic/Color" title="Vælg design" style="Disabled"/></row>'.
'<row><icon icon="Web/Frame" title="Vælg opsætning" style="Disabled"/></row>';
if ($info['fixedHierarchy']==0) {
	$gui.='<row><icon icon="Element/Structure" title="Menupunkt" style="Disabled"/></row>';
	$back = 'NewPageHierarchyItem.php';
}
else {
	$back = 'NewPageFrame.php';
}
$gui.=
'<row><icon icon="Basic/Info" title="Angiv egenskaber" style="Hilited"/></row>'.
'</group>'.
'</cell><cell width="99%">'.
'<area xmlns="uri:Area" width="100%" height="100%"><content padding="10">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>Angiv egenskaber</strong>'.
'<break/><small>Udfyld felterne med sidens egenskaber</small>'.
'</text>'.
'<overflow xmlns="uri:Layout" height="300">'.
'<form xmlns="uri:Form" action="CreatePage.php" method="post" name="Formula" focus="title" submit="true" object="Form">'.
'<validation>
if (Title.isEmpty()) {
	Title.setError("Titlen skal udfyldes");
	Title.blinkError(1000);
	Title.focus();
	return false;
}
else {
	return true;
}
</validation>'.
'<group size="Large">'.
'<space/>'.
'<textfield badge="Titel:" name="title" hint="En titel der kort beskriver sidens indhold eller funktion" object="Title"/>'.
'<textfield badge="Beskrivelse:" name="description" lines="6" hint="En længere opsummering af sidens indhold"/>'.
'<space/>'.
'<textfield badge="Nølgeord:" name="keywords" hint="Ekstra ord til brug i søgning"/>'.
'<select badge="Sprog" name="language" hint="Sidens primære sprog">'.
'<option title="" value=""/>';
$languages = GuiUtils::getLanguages();
while ($language = current($languages)) {
    $gui.='<option value="'.key($languages).'" title="'.$language.'"/>';
    next($languages);
}
$gui.=
'</select>'.
'</group>'.
'</form>'.
'</overflow>'.
'</content></area>'.
'</cell></row>'.
'<row><cell colspan="2">'.
'<group size="Large" xmlns="uri:Button" align="right" top="5">'.
'<button title="Annuller" link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'<button title="Forrige" link="'.$back.'" help="Gå tilbage til forrige punkt"/>'.
'<button title="Opret" style="Hilited" link="javascript: Form.submit();" help="Klik her for at oprette den nye side"/>'.
'</group>'.
'</cell></row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Area","Layout","Form","Text","Icon","Button");
writeGui($xwg_skin,$elements,$gui);
?>