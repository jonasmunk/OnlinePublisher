<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Persongroup.php';
require_once 'Functions.php';


$group=getPersonGroup();

if ($group>0) {
	$persongroup = PersonGroup::load($group);
}

$images = GuiUtils::buildObjectOptions('image',20);



$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="600" align="center">'.
'<titlebar title="Ny person" icon="Element/Person">'.
'<close link="'.($group>0 ? 'Persongroup.php' : 'Library.php').'"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="CreatePerson.php" method="post" name="Formula" focus="firstname" submit="true">'.
'<layout xmlns="uri:Layout" width="100%">'.
'<row><cell width="300" valign="top">'.
'<group xmlns="uri:Form" size="Large">'.
'<box title="Navn">'.
'<textfield badge="Fornavn:" name="firstname"/>'.
'<textfield badge="Mellemnavn:" name="middlename"/>'.
'<textfield badge="Efternavn:" name="surname"/>'.
'<textfield badge="Initialer:" name="initials"/>'.
'<textfield badge="Kaldenavn:" name="nickname"/>'.
'</box>'.
'<box title="Diverse">'.
'<textfield badge="Jobtitel:" name="jobtitle"/>'.
'<select badge="Køn:" name="sex" selected="1">'.
'<option title="Mand" value="1"/>'.
'<option title="Kvinde" value="0"/>'.
'</select>'.
'<select badge="Billede:" name="imageid">'.
'<option title="" value="0"/>'.
$images.
'</select>'.
'<textfield badge="Web-adresse:" name="webaddress"/>'.
'</box>'.
'</group>'.
'</cell><cell width="300">'.
'<group xmlns="uri:Form" size="Large">'.
'<box title="Adresse">'.
'<textfield badge="Gade:" name="streetname"/>'.
'<textfield badge="Postnr:" name="zipcode"/>'.
'<textfield badge="By:" name="city"/>'.
'<textfield badge="Land:" name="country"/>'.
'</box>'.
'<box title="Telefon">'.
'<textfield badge="Privat:" name="phone_private"/>'.
'<textfield badge="Job:" name="phone_job"/>'.
'</box>'.
'<box title="E-mail">'.
'<textfield badge="Privat:" name="email_private"/>'.
'<textfield badge="Job:" name="email_job"/>'.
'</box>'.
'</group>'.
'</cell></row><row><cell colspan="2">'.
'<group xmlns="uri:Form" size="Large">'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.($group>0 ? 'Persongroup.php' : 'Library.php').'"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</cell></row></layout>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Window","Form","Layout");
writeGui($xwg_skin,$elements,$gui);
?>