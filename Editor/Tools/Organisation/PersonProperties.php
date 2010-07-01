<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Persongroup.php';
require_once 'Functions.php';


$id = requestGetNumber('id',0);
$group = requestGetNumber('group',-1);
if ($group>-1) {
	setPersonGroup($group);
}
$group=getPersonGroup();

$person = Person::load($id);

$images = GuiUtils::buildObjectOptions('image',20);



$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="600" align="center">'.
'<sheet width="350" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du virkelig slette personen?</title>'.
'<description>Handlingen kan ikke fortrydes og personen fjernes fra alle sider!</description>'.
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();"/>'.
'<button title="Slet" link="DeletePerson.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<titlebar title="'.encodeXML($person->getTitle()).'" icon="Element/Person">'.
'<close link="'.($group>0 ? 'Persongroup.php' : 'Library.php').'"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdatePerson.php" method="post" name="Formula" focus="firstname">'.
'<hidden name="id">'.$id.'</hidden>'.
'<layout xmlns="uri:Layout" width="100%">'.
'<row><cell width="50%" valign="top">'.
'<group xmlns="uri:Form" size="Large">'.
'<box title="Navn">'.
'<textfield badge="Fornavn:" name="firstname">'.encodeXML($person->getFirstname()).'</textfield>'.
'<textfield badge="Mellemnavn:" name="middlename">'.encodeXML($person->getMiddlename()).'</textfield>'.
'<textfield badge="Efternavn:" name="surname">'.encodeXML($person->getSurname()).'</textfield>'.
'<textfield badge="Initialer:" name="initials">'.encodeXML($person->getInitials()).'</textfield>'.
'<textfield badge="Kaldenavn:" name="nickname">'.encodeXML($person->getNickname()).'</textfield>'.
'</box>'.
'<box title="Diverse">'.
'<textfield badge="Jobtitel:" name="jobtitle">'.encodeXML($person->getJobtitle()).'</textfield>'.
'<select badge="Køn:" name="sex" selected="'.$person->getSex().'">'.
'<option title="Mand" value="1"/>'.
'<option title="Kvinde" value="0"/>'.
'</select>'.
'<select badge="Billede:" name="imageid" selected="'.$person->getImage_id().'">'.
'<option title="" value="0"/>'.
$images.
'</select>'.
'<textfield badge="Web-adresse:" name="webaddress">'.encodeXML($person->getWebaddress()).'</textfield>'.
'<checkbox badge="Søgbar:" name="searchable" selected="'.($person->isSearchable() ? 'true' : 'false').'"/>'.
'</box>'.
'</group>'.
'</cell><cell>'.
'<group xmlns="uri:Form" size="Large">'.
'<box title="Adresse">'.
'<textfield badge="Gade:" name="streetname">'.encodeXML($person->getStreetname()).'</textfield>'.
'<textfield badge="Postnr:" name="zipcode">'.encodeXML($person->getZipcode()).'</textfield>'.
'<textfield badge="By:" name="city">'.encodeXML($person->getCity()).'</textfield>'.
'<textfield badge="Land:" name="country">'.encodeXML($person->getCountry()).'</textfield>'.
'</box>'.
'<box title="Telefon">'.
'<textfield badge="Privat:" name="phone_private">'.encodeXML($person->getPhone_private()).'</textfield>'.
'<textfield badge="Job:" name="phone_job">'.encodeXML($person->getPhone_job()).'</textfield>'.
'</box>'.
'<box title="E-mail">'.
'<textfield badge="Privat:" name="email_private">'.encodeXML($person->getEmail_private()).'</textfield>'.
'<textfield badge="Job:" name="email_job">'.encodeXML($person->getEmail_job()).'</textfield>'.
'</box>'.
'</group>'.
'</cell></row><row><cell colspan="2">'.
'<group xmlns="uri:Form" size="Large">'.
'<buttongroup size="Large">'.
'<button title="Slet" link="javascript: ConfirmDelete.show();"/>'.
'<button title="Annuller" link="'.($group>0 ? 'Persongroup.php' : 'Library.php').'"/>'.
($person->isPublished() ?
'<button title="Udgiv" style="Disabled"/>' :
'<button title="Udgiv" link="PublishPerson.php?id='.$id.'"/>'
).
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</cell></row></layout>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Window","Form","Layout","Message");
writeGui($xwg_skin,$elements,$gui);
?>