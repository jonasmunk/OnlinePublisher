<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Links" state="list">
	<controller source="controller.js"/>
	<source name="graphSource" url="data/GraphData.php">
		<parameter key="source" value="@sourceSelector.value"/>
		<parameter key="target" value="@targetSelector.value"/>
		<parameter key="state" value="@stateSelector.value"/>
	</source>
	<source name="listSource" url="data/LinkList.php">
		<parameter key="source" value="@sourceSelector.value"/>
		<parameter key="target" value="@targetSelector.value"/>
		<parameter key="state" value="@stateSelector.value"/>
	</source>
	<structure>
		<top>
			<toolbar>
				<field label="{View; da:Visning}">
					<segmented value="list" name="view">
						<item icon="view/list" value="list"/>
						<item icon="view/graph" value="graph"/>
					</segmented>
				</field>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="all" name="stateSelector">
					<title>Status</title>
					<item icon="monochrome/round_question" title="{All; da:Alle}" value="all"/>
					<item icon="monochrome/warning" title="{Errors; da:Fejl}" value="warnings"/>
				</selection>
				<selection value="all" name="sourceSelector">
					<title>{Source; da:Kilde}</title>
					<item icon="common/folder" title="{All; da:Alle}" value="all"/>
					<item icon="common/page" title="{Pages; da:Sider}" value="page"/>
					<item icon="common/news" title="{News items; da:Nyheder}" value="news"/>
					<item icon="common/hierarchy" title="{Hierarchies; da:Hierarkier}" value="hierarchy"/>
				</selection>
				<selection value="all" name="targetSelector">
					<title>{Target; da:Mål}</title>
					<item icon="common/folder" title="{All; da:Alle}" value="all"/>
					<item icon="common/internet" title="{Addresses; da:Adresser}" value="url"/>
					<item icon="common/email" title="{E-mails; da:E-post-adresser}" value="email"/>
					<item icon="common/page" title="{Pages; da:Sider}" value="page"/>
					<item icon="file/generic" title="{Files; da:Filer}" value="file"/>
				</selection>
			</left>
			<center>
				<overflow>
					<list name="list" source="listSource" state="list"/>
					<graph source="graphSource" name="graph" layout="d3" state="graph"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
</gui>';

In2iGui::render($gui);
?>