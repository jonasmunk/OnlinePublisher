<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="{People; da:Personer}">
	<controller source="controller.js"/>
	<source name="personGroupSource" url="../../Services/Model/Items.php?type=persongroup"/>
	<source name="mailinglistSource" url="../../Services/Model/Items.php?type=mailinglist"/>
	<source name="personListSource" url="data/ListPersons.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="kind" value="@selector.kind"/>
		<parameter key="value" value="@selector.value"/>
	</source>
	<source name="mailinglistListSource" url="../../Services/Model/ListObjects.php?type=mailinglist">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
	</source>
	<source name="groupListSource" url="../../Services/Model/ListObjects.php?type=persongroup">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
	</source>
	
	<structure>
		<top>
		<toolbar>
			<icon icon="common/user" title="{New person; da:Ny person}" name="newPerson" overlay="new"/>
			<icon icon="common/folder" title="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
			<icon icon="common/email" title="{New mailing list; da:Ny postliste}" name="newMailinglist" overlay="new"/>
			<divider/>
			<icon icon="common/letter" title="{Send e-mail; da:Afsend e-post}" name="sendEmail"/>
			<right>
				<field label="{Search; da:Søgning}">
					<searchfield name="search" expanded-width="200"/>
				</field>
			</right>
		</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="person" name="selector">
						<item icon="common/person" title="{All people; da:Alle personer}" value="person"/>
						<item icon="common/email" title="{All mailing lists; da:Alle postlister}" value="mailinglist"/>
						<item icon="common/folder" title="{All groups; da:Alle grupper}" value="persongroup"/>
						<items name="groupSelection" source="personGroupSource" title="{Groups; da:Grupper}"/>
						<items name="mailinglistSelection" source="mailinglistSource" title="{Mailing lists; da:Postlister}"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="personListSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window name="personEditor" width="460" title="Person">
		<formula name="personFormula">
			<tabs small="true" centered="true">
				<tab title="Person" padding="10">
					<columns flexible="true" space="10">
						<column>
							<columns space="10">
								<column>
									<field label="{First name; da:Fornavn}:">
										<text-input name="personFirstname"/>
									</field>
								</column>
								<column>
									<field label="{Middle name; da:Mellemnavn}:">
										<text-input name="personMiddlename"/>
									</field>
								</column>
								<column>
									<field label="{Last name; da:Efternavn}:">
										<text-input name="personSurname"/>
									</field>
								</column>
							</columns>
							<columns space="10">
								<column>
									<field label="{Job title; da:Jobtitel}:">
										<text-input name="personJobtitle"/>
									</field>
								</column>
								<column>
									<field label="{Initials; da:Initialer}:">
										<text-input name="personInitials"/>
									</field>
								</column>
								<column>
									<field label="{Call name; da:Kaldenavn}:">
										<text-input name="personNickname"/>
									</field>
								</column>
							</columns>
						</column>
						<column width="74px">
							<field label="{Image; da:Billede}">
								<image-input name="personImage" size="68">
									<finder title="{Select image; da:Vælg billede}" 
										list-url="../../Services/Finder/ImagesList.php"
										selection-url="../../Services/Finder/ImagesSelection.php"
										selection-value="all"
										selection-parameter="group"
										search-parameter="query"
									/>
								</image-input>
							</field>
						</column>
					</columns>
					<columns space="10">
						<column>
							<fields labels="above">
								<field label="{E-mail; da:E-post}:">
									<objectlist name="personEmails">
										<text key="address"/>
									</objectlist>
								</field>
								<field label="{Phone; da:Telefon}:">
									<objectlist name="personPhones">
										<text key="number" label="{Number; da:Nummer}"/>
										<text key="context" label="{Context; da:Kontekst}"/>
									</objectlist>
								</field>
							</fields>
							<fields>
								<field label="{Gender; da:Køn}:">
									<radiobuttons name="personSex" value="1">
										<radiobutton value="1" label="{Male; da:Mand}"/>
										<radiobutton value="0" label="{Female; da:Kvinde}"/>
									</radiobuttons>
								</field>
							</fields>
						</column>
						<column>
							<field label="{Address; da:Adresse}:">
								<text-input name="personStreetname"/>
							</field>
							<columns space="10">
								<column>
									<field label="{Postal code; da:Postnr}:">
										<text-input name="personZipcode"/>
									</field>
								</column>
								<column>
									<field label="{City; da:By}:">
										<text-input name="personCity"/>
									</field>
								</column>
							</columns>
							<fields labels="above">
								<field label="{Country; da:Land}:">
									<text-input name="personCountry"/>
								</field>
								<field label="{Web address; da:Internetadresse}:">
									<text-input name="personWebaddress"/>
								</field>
							</fields>
						</column>
					</columns>
				</tab>
				<tab title="{Settings; da:Indstillinger}" padding="10">
					<columns space="5">
						<column>
							<fields>
								<field label="{Mailing lists; da:Postlister}:">
									<checkboxes name="personMailinglists">
										<items source="mailinglistSource"/>
									</checkboxes>
								</field>
								<field label="{Searchable; da:Søgbar}:">
									<checkbox name="personSearchable"/>
								</field>
							</fields>
						</column>
						<column>
							<fields>
								<field label="{Groups; da:Grupper}:">
									<checkboxes name="personGroups">
										<items source="personGroupSource"/>
									</checkboxes>
								</field>
							</fields>
						</column>
					</columns>
				</tab>
				<tab title="Information" padding="10">
					<field label="{Note; da:Notat}:">
						<text-input name="personNote" multiline="true"/>
					</field>
				</tab>
			</tabs>
			<buttons padding="5">
				<button name="cancelPerson" title="{Cancel; da:Annuller}"/>
				<button name="deletePerson" title="{Delete; da:Slet}">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete person; da: Ja, slet person}" cancel="{No; da:Nej}"/>
				</button>
				<button name="savePerson" title="{Save; da:Gem}" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>
	
	<window name="mailinglistEditor" width="300" title="{Mailing list; da:Postliste}" padding="5">
		<formula name="mailinglistFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input name="mailinglistTitle"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input name="mailinglistNote" lines="10"/>
				</field>
				<buttons top="5">
					<button name="cancelMailinglist" title="{Cancel; da:Annuller}"/>
					<button name="deleteMailinglist" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete mailing list; da: Ja, slet postliste}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveMailinglist" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="groupEditor" width="300" title="{Group; da:Gruppe}" padding="5">
		<formula name="groupFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input name="groupTitle"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input name="groupNote" multiline="true"/>
				</field>
				<buttons>
					<button name="cancelGroup" title="{Cancel; da:Annuller}"/>
					<button name="deleteGroup" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete group; da: Ja, slet gruppe}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveGroup" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
</gui>';
In2iGui::render($gui);
?>