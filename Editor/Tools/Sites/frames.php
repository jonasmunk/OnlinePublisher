<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Special pages">
	<source url="data/ListFrames.php" name="listSource"/>
	<controller source="js/frames.js"/>
	<source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
	<source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
	<source name="newsGroupItems" url="../../Services/Model/Items.php?type=newsgroup"/>
	<source name="hierarchyItems" url="data/HierarchyItems.php"/>
	<toolbar>
		<icon icon="common/page" overlay="new" text="Tilføj ramme" name="newFrame"/>
	</toolbar>
	<overflow>
		<list name="list" source="listSource"/>
	</overflow>
	
	<window name="frameWindow" width="500" title="Ramme">
		<tabs small="true" centered="true">
			<tab title="Info" padding="5">
				<formula name="frameFormula">
					<group>
						<text label="Navn:" key="name"/>
						<text label="Titel:" key="title"/>
						<text label="Bund-tekst:" key="bottomText" multiline="true"/>
						<dropdown key="hierarchyId" label="Hierarki:" source="hierarchyItems" placeholder="Vælg..."/>
					</group>
				</formula>
			</tab>
			<tab title="Søgning" padding="5">
				<formula name="searchFormula">
					<group>
						<checkbox label="Aktiv:" key="enabled"/>
						<dropdown key="pageId" label="Søgeside:" source="pageItems" placeholder="Vælg søgeside..."/>
					</group>
				</formula>
			</tab>
			<tab title="Bruger" padding="5">
				<formula name="userFormula">
					<group>
						<checkbox label="Aktiv:" key="enabled"/>
						<dropdown key="pageId" label="Login-side:" source="pageItems" placeholder="Vælg login-side..."/>
					</group>
				</formula>
			</tab>
			<tab title="Top-links">
				<toolbar centered="true">
					<icon title="Tilføj link" icon="common/link" overlay="new" click="topLinks.addLink()"/>
				</toolbar>
				<links name="topLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
			<tab title="Bund-links">
				<toolbar centered="true">
					<icon title="Tilføj link" icon="common/link" overlay="new" click="bottomLinks.addLink()"/>
				</toolbar>
				<links name="bottomLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
			<tab title="Nyheder">
				<toolbar centered="true">
					<icon title="Tilføj blok" icon="common/new" name="addNewsBlock"/>
				</toolbar>
				<list name="newsList">
					<column title="Titel" key="title"/>
				</list>
			</tab>
		</tabs>
		<space all="5">
		<buttons align="right">
			<button name="cancelFrame" title="Annuller"/>
			<button name="deleteFrame" title="Slet">
				<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
			</button>
			<button name="saveFrame" title="Gem" highlighted="true"/>
		</buttons>
		</space>
	</window>
	
	<window name="newsWindow" width="300" title="Nyhedsblok" padding="5">
		<formula name="newsFormula">
			<group>
				<text label="Titel:" key="title"/>
			</group>
			<overflow height="300">
			<fieldset legend="Visning">
				<group>
					<dropdown label="Sorter efter" key="sortby">
						<item value="startdate" title="Startdato"/>
						<item value="enddate" title="Slutdato"/>
						<item value="title" title="Titel"/>
					</dropdown>
					<dropdown label="Retning" key="sortdir">
						<item value="ascending" title="Stigende"/>
						<item value="descending" title="Faldende"/>
					</dropdown>
					<dropdown label="Maks. antal" key="maxitems">
						<item value="0" title="Uendeligt"/>
						<item value="1" title="1"/>
						<item value="2" title="2"/>
						<item value="3" title="3"/>
						<item value="4" title="4"/>
						<item value="5" title="5"/>
						<item value="6" title="6"/>
						<item value="7" title="7"/>
						<item value="8" title="8"/>
						<item value="9" title="9"/>
						<item value="10" title="10"/>
						<item value="11" title="11"/>
						<item value="12" title="12"/>
						<item value="13" title="13"/>
						<item value="14" title="14"/>
						<item value="15" title="15"/>
						<item value="20" title="20"/>
						<item value="25" title="25"/>
					</dropdown>
				</group>
			</fieldset>
			<fieldset legend="Tid">
				<group>
					<dropdown key="timetype" label="Tid">
						<item value="always" title="Altid"/>
						<item value="now" title="Lige nu"/>
						<item value="interval" title="Interval"/>
						<item value="hours" title="Seneste timer..."/>
						<item value="days" title="Seneste dage..."/>
						<item value="weeks" title="Seneste uger..."/>
						<item value="months" title="Seneste måneder..."/>
						<item value="years" title="Seneste år..."/>
					</dropdown>
					<dropdown label="Antal" key="timecount">
						<item value="0" title="Uendeligt"/>
						<item value="1" title="1"/>
						<item value="2" title="2"/>
						<item value="3" title="3"/>
						<item value="4" title="4"/>
						<item value="5" title="5"/>
						<item value="6" title="6"/>
						<item value="7" title="7"/>
						<item value="8" title="8"/>
						<item value="9" title="9"/>
						<item value="10" title="10"/>
					</dropdown>
					<datetime key="startdate" label="Fra" return-type="seconds"/>
					<datetime key="enddate" label="Til" return-type="seconds"/>
				</group>
			</fieldset>
			<fieldset legend="Nyheder">
				<group>
					<field label="Grupper">
						<checkboxes key="groups">
							<items source="newsGroupItems"/>
						</checkboxes>
					</field>
				</group>
			</fieldset>
			</overflow>
			<buttons align="right" top="5">
				<button name="cancelNews" title="Annuller"/>
				<button name="deleteNews" title="Slet">
					<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
				</button>
				<button name="saveNews" title="Gem" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>

</gui>';
In2iGui::render($gui);
?>