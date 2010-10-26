<?php
/**
 * Displays the base frameset of the internal system
 *
 * @package OnlinePublisher
 * @subpackage Base
 * @category Interface
 */
if (!file_exists('../Config/Setup.php')) {
	header('Location: ../setup/initial/');
	exit;
}
require_once '../Config/Setup.php';
require_once 'Include/Security.php';

$start='Services/Start/';
if (Request::exists("page")) {
	$page=Request::getInt('page');
	InternalSession::setPageId($page);
	$start='Services/Preview/';
}
$gui='
<gui xmlns="uri:In2iGui" title="OnlinePublisher editor">
	<dock url="'.$start.'" name="dock" position="bottom" frame-name="Desktop">
		<tabs small="true">';
			$tabs = array('edit'=>'{ Editing ; da: Redigering }','analyse'=>'{Analysis ; da:Analyse}','setup'=>'{ Setup ; da:Opsætning }');
			foreach ($tabs as $tab => $tabTitle) {
				$tools = InternalSession::getToolsByCategory($tab);
				if ($tools) {
					$gui.='<tab title="'.$tabTitle.'" background="light"><toolbar>';
					foreach ($tools as $tool) {
						$gui.='<icon title="'.$tool['name'].'" icon="'.$tool['icon'].'" action="dock.setUrl(\'Tools/'.$tool['unique'].'/\')"/>';
					}
					$gui.='
					<right>
					<icon title="{ View ; da:Vis }" icon="common/view" action="dock.setUrl(\'Services/Preview/\')"/>
					<icon title="{ Edit ; da:Rediger }" icon="common/edit" action="dock.setUrl(\'Template/Edit.php/\')"/>
					<icon title="{ Publish ; da:Udgiv }" icon="common/internet" overlay="upload" action="dock.setUrl(\'Services/Publish/?close=../../Services/Start/\')"/>
					<!--<divider/>
					<search title="Søgning"/>-->
					<divider/>
					<icon title="Start" icon="common/play" action="dock.setUrl(\'Services/Start/\')"/>
					<icon title="{ Exit ; da: Log ud }" icon="common/stop" action="document.location=\'Authentication.php?logout=true\'"/>
					</right>
					</toolbar></tab>';
				}
			}
			$gui.='
		</tabs>
	</dock>
</gui>';

In2iGui::render($gui);
?>
