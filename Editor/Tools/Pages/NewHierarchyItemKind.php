<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';
require_once 'Functions.php';

$close = InternalSession::getToolSessionVar('pages','rightFrame');
$hierarchy = Request::getInt('hierarchy');
$parent = Request::getInt('parent');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Nyt underpunkt" icon="Basic/Add">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="15">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>Hvilken type underpunkt vil du oprette?</strong>'.
'</text>'.
'<group xmlns="uri:Icon" size="3" spacing="10" width="100%" cellwidth="50%">'.
'<row>'.
'<icon icon="Template/Document" overlay="New" title="Ny side" link="NewPageTemplate.php?parent='.$parent.'&amp;hierarchy='.$hierarchy.'"/>'.
'<icon icon="Web/Link" title="Link" link="NewHierarchyItem.php?parent='.$parent.'&amp;hierarchy='.$hierarchy.'"/>'.
'</row>'.
'</group>'.
'<group xmlns="uri:Button" align="center" size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'</group>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';
$elements = array("Window","Icon","Text","Button");
writeGui($xwg_skin,$elements,$gui);
?>