<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Special pages">
	<source url="data/ListFrames.php" name="listSource"/>
	<controller source="frames.js"/>
	<source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
	<source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
	<source name="newsGroupItems" url="../../Services/Model/Items.php?type=newsgroup"/>
	<source name="hierarchyItems" url="data/HierarchyItems.php"/>
	<toolbar>
		<icon icon="common/page" overlay="new" text="{New setup; da:Ny opsætning}" name="newFrame"/>
	</toolbar>
	<overflow>
		<list name="list" source="listSource"/>
	</overflow>
	
	<window name="frameWindow" width="500" title="{Setup; da:Opsætning}">
		<tabs small="true" centered="true">
			<tab title="Info" padding="5">
				<formula name="frameFormula">
					<fields>
						<field label="{Name; da:Navn}:">
							<text-input key="name"/>
						</field>
						<field label="{Title; da:Titel}:">
							<text-input key="title"/>
						</field>
						<field label="{Bottom text; da:Bund-tekst}:">
							<text-input key="bottomText" multiline="true"/>
						</field>
						<field label="{Hierarchy; da:Hierarki}:">
							<dropdown key="hierarchyId" source="hierarchyItems" placeholder="{Choose hierarchy; da:Vælg hierarki...}"/>
						</field>
					</fields>
				</formula>
			</tab>
			<tab title="{Search; da:Søgning}" padding="5">
				<formula name="searchFormula">
					<fields>
						<field label="{Active; da:Aktiv}:">
							<checkbox key="enabled"/>
						</field>
						<field label="{Search page; da:Søgeside}:">
							<dropdown key="pageId" source="pageItems" placeholder="{Choose search page...; da:Vælg søgeside...}"/>
						</field>
					</fields>
				</formula>
			</tab>
			<tab title="{User; da:Bruger}" padding="5">
				<formula name="userFormula">
					<fields>
						<field label="{Active; da:Aktiv}:">
							<checkbox key="enabled"/>
						</field>
						<field label="{Login page; da:Login-side}:">
							<dropdown key="pageId" source="pageItems" placeholder="{Choose login page...; da:Vælg login-side...}"/>
						</field>
					</fields>
				</formula>
			</tab>
			<tab title="{Top links; da:Top-links}">
				<toolbar centered="true">
					<icon title="{New link; da:Nyt link}" icon="common/link" overlay="new" click="topLinks.addLink()"/>
				</toolbar>
				<links name="topLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
			<tab title="{Bottom links; da:Bund-links}">
				<toolbar centered="true">
					<icon title="{New link; da:Nyt link}" icon="common/link" overlay="new" click="bottomLinks.addLink()"/>
				</toolbar>
				<links name="bottomLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
			<tab title="{News; da:Nyheder}">
				<toolbar centered="true">
					<icon title="{New block; da:Ny blok}" icon="common/new" name="addNewsBlock"/>
				</toolbar>
				<list name="newsList">
					<column title="{Title; da:Titel}" key="title"/>
				</list>
			</tab>
		</tabs>
		<space all="5">
		<buttons align="right">
			<button name="cancelFrame" title="{Cancel; da:Annuller}"/>
			<button name="deleteFrame" title="{Delete; da:Slet}">
				<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
			</button>
			<button name="saveFrame" title="{Save; da:Gem}" highlighted="true"/>
		</buttons>
		</space>
	</window>
	
	<window name="newsWindow" width="400" title="{News block; da:Nyhedsblok}" padding="5">
		<formula name="newsFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input key="title"/>
				</field>
			</fields>
			<overflow height="250">
			<space all="5">
			<fieldset legend="{Appearance; da:Visning}">
				<fields>
					<field label="{Sort by; da:Sorter efter}">
						<dropdown key="sortby">
							<item value="startdate" title="{Satrt date; da:Startdato}"/>
							<item value="enddate" title="{End date; da:Slutdato}"/>
							<item value="title" title="{Title; da:Titel}"/>
						</dropdown>
					</field>
					<field label="{Direction; da:Retning}">
						<dropdown key="sortdir">
							<item value="ascending" title="{Ascending; da:Stigende}"/>
							<item value="descending" title="{Descending; da:Faldende}"/>
						</dropdown>
					</field>
					<field label="{Max items; da:Maks. antal}">
						<dropdown key="maxitems">
							<item value="0" title="{Infinite; da:Uendeligt}"/>
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
					</field>
				</fields>
			</fieldset>
			<space height="10"/>
			<fieldset legend="{Time; da:Tid}">
				<fields>
					<field label="{Time; da:Tid}">
						<dropdown key="timetype">
							<item value="always" title="{Always; da:Altid}"/>
							<item value="now" title="{Right now; da:Lige nu}"/>
							<item value="interval" title="{Interval; da:Interval}"/>
							<item value="hours" title="{Latest hours; da:Seneste timer}..."/>
							<item value="days" title="{Latest days; da:Seneste dage}..."/>
							<item value="weeks" title="{Latest weeks; da:Seneste uger}..."/>
							<item value="months" title="{Latest months; da:Seneste måneder}..."/>
							<item value="years" title="{Latest years; da:Seneste år}..."/>
						</dropdown>
						<dropdown key="timecount">
							<item value="0" title="{Infinite; da:Uendeligt}"/>
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
					</field>
					<field label="{From; da:Fra}">
						<datetime-input key="startdate" return-type="seconds"/>
					</field>
					<field label="{To; da:Til}">
						<datetime-input key="enddate" return-type="seconds"/>
					</field>
				</fields>
			</fieldset>
			<space height="10"/>
			<fieldset legend="{News; da:Nyheder}">
				<fields>
					<field label="{Groups; da:Grupper}">
						<checkboxes key="groups">
							<items source="newsGroupItems"/>
						</checkboxes>
					</field>
				</fields>
			</fieldset>
			</space>
			</overflow>
			<buttons align="right" top="5">
				<button name="cancelNews" title="{Cancel; da:Annuller}"/>
				<button name="deleteNews" title="{Delete; da:Slet}">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
				</button>
				<button name="saveNews" title="{Save; da:Gem}" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>

</gui>';
In2iGui::render($gui);
?>