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

$categorized = ToolService::getCategorized();

$lang = InternalSession::getLanguage();

$gui='
<gui xmlns="uri:hui" title="OnlinePublisher editor">
	<controller source="Base.js"/>
	<dock url="'.$start.'" name="dock" position="bottom" frame-name="Desktop">
		<!--<sidebar>
			<searchfield adaptive="true"/>
		</sidebar>-->
		<tabs small="true">';
			$tabs = array('edit'=>'{ Editing ; da: Redigering }','analyse'=>'{Analysis ; da:Analyse}','setup'=>'{ Setup ; da:Opsætning }');
			foreach ($tabs as $tab => $tabTitle) {
				$tools = $categorized[$tab];
				if ($tools) {
					$gui.='<tab title="'.$tabTitle.'" background="light"><toolbar name="'.$tab.'Toolbar">';
					foreach ($tools as $key => $tool) {
						$gui.='<icon title="'.$tool->name->$lang.'" icon="'.$tool->icon.'" action="dock.setUrl(\'Tools/'.$tool->key.'/\')" key="tool:'.$tool->key.'"/>';
					}
					$gui.='
					<right>
					<icon title="{ View ; da:Vis }" icon="common/view" action="dock.setUrl(\'Services/Preview/\')" key="service:preview"/>
					<icon title="{ Edit ; da:Rediger }" icon="common/edit" action="dock.setUrl(\'Template/Edit.php/\')" key="service:edit"/>
					<icon title="{ Publish ; da:Udgiv }" icon="common/internet" overlay="upload" action="baseController.goPublish()" key="service:publish"/>
					<!--<divider/>
					<search title="Søgning"/>-->
					<divider/>
					<icon title="Start" icon="common/play" action="dock.setUrl(\'Services/Start/\')" key="service:start"/>
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
