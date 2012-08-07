<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Butik">
	<controller source="controller.js"/>
	<source name="productListSource" url="data/ListProducts.php"/>
	<source name="groupSource" url="../../Services/Model/Items.php?type=productgroup"/>
	<source name="typeSource" url="../../Services/Model/Items.php?type=producttype"/>
	<structure>
		<top>
		<toolbar>
			<icon icon="common/product" title="{New product; da:Nyt produkt}" name="newProduct" overlay="new"/>
			<icon icon="common/folder" title="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
			<icon icon="common/folder" title="{New type; da:Ny type}" name="newType" overlay="new"/>
		</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="product" name="selector">
					<item icon="common/product" title="{All products; da:Alle produkter}" value="product"/>
					<item icon="common/email" title="{All offers; da:Alle bud}" value="productoffer"/>
					<item icon="common/folder" title="{All groups; da:Alle grupper}" value="productgroup"/>
					<items source="groupSource" title="{Groups; da:Grupper}"/>
					<items source="typeSource" title="{Types; da:Typer}"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="productListSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window name="offerEditor" width="300" title="{Offer; da:Bud}" pad="5">
		<formula name="offerFormula">
			<fields>
				<field label="{Offer; da:Bud}:">
					<text-input name="offerOffer"/>
				</field>
				<field label="Deadline:">
					<datetime-input name="offerExpiry"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input name="offerNote" lines="6"/>
				</field>
			</fields>
			<buttons>
				<button name="cancelOffer" title="{Cancel; da:Annuller}"/>
				<button name="deleteOffer" title="{Delete; da:Slet}">
					<confirm text="{Are you sure? da:Er du sikker?}" ok="{Yes, delete offer; da:Ja, slet bud}" cancel="{No; da:Nej}"/>
				</button>
				<button name="saveOffer" title="{Save; da:Gem}" highlighted="true"/>
			</buttons>
		</formula>
	</window>
	
	<window name="groupEditor" width="300" title="{Group; da:Gruppe}" pad="5">
		<formula name="groupFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input key="title"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input key="note" lines="10"/>
				</field>
				<buttons>
					<button name="cancelGroup" title="{Cancel; da:Annuller}"/>
					<button name="deleteGroup" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete group; da:Ja, slet gruppen}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveGroup" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="typeEditor" width="300" title="Type" pad="5">
		<formula name="typeFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input name="typeTitle"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input name="typeNote" lines="10"/>
				</field>
				<buttons>
					<button name="cancelType" title="{Cancel; da:Annuller}"/>
					<button name="deleteType" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete type; da:Ja, slet typen}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveType" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="productEditor" width="500" title="{Product; da:Produkt}">
		<formula name="productFormula">
			<tabs small="true" centered="true">
				<tab title="{Product; da:Produkt}" padding="5">
					<columns space="10">
						<column>
							<field label="{Title; da:Titel}:">
								<text-input name="productTitle"/>
							</field>
						</column>
						<column>
							<field label="{Number; da:Nummer}:">
								<text-input name="productNumber"/>
							</field>
						</column>
						<column>
							<field label="Type">
								<dropdown name="productType" source="typeSource" adaptive="true"/>
							</field>
						</column>
					</columns>
					<columns flexible="true" space="5">
						<column>
							<field label="{Description; da:Beskrivelse}:">				
								<text-input name="productNote" multiline="true"/>
							</field>
						</column>
						<column width="60px">
							<field label="{Image; da:Billede}:">
								<image-input name="productImage" source="../../Services/Model/ImagePicker.php"/>
							</field>
						</column>
					</columns>
					<fields labels="above">
						<field label="{Attributes; da:Attributter}:">
							<objectlist name="productAttributes">
								<text key="name" label="{Name; da:Navn}"/>
								<text key="value" label="{Value; da:Værdi}"/>
							</objectlist>
						</field>
					</fields>
				</tab>
				<tab title="{Prices; da:Priser}" padding="5">
				<fields labels="above">
					<field label="{Prices; da:Priser}:">
						<objectlist name="productPrices">
							<text key="amount" label="{Amount; da:Antal}"/>
							<select label="{Unit; da:Enhed}:" key="type">
								<option value="unit" label="{unit; da:enhed}"/>
								<option value="meter" label="{meters; da:meter}"/>
								<option value="squaremeter" label="{square meters; da:kvadratmeter}"/>
								<option value="cubicmeter" label="{cubic meters; da:kubikmeter}"/>
								<option value="gram" label="{grams; da:gram}"/>
							</select>
							<text key="price" label="{Price; da:Pris}"/>
							<select key="currency" label="{Currency; da:Valuta}">
								<option value="DKK" label="{Danish krone; da:Dansk krone}"/>
								<option value="EUR" label="Euro"/>
								<option value="USD" label="{American Dolar; da:Amerikansk dollar}"/>
							</select>
						</objectlist>
					</field>
				</fields>
				</tab>
				<tab title="{Settings; da:Indstillinger}" padding="5">
					<columns>
						<column>
							<fields>
								<field label="{Searchable; da:Søgbar}:">
									<checkbox name="productSearchable"/>
								</field>
								<field label="{Allow offer; da:Tillad bud}:">
									<checkbox name="productAllowOffer"/>
								</field>
							</fields>
						</column>
						<column>
							<fields>
								<field label="{Groups; da:Grupper}:">
									<checkboxes name="productGroups">
										<items source="groupSource"/>
									</checkboxes>
								</field>
							</fields>
						</column>
					</columns>
				</tab>
			</tabs>
			<fields>
				<buttons>
					<button name="cancelProduct" title="{Cancel; da:Annuller}"/>
					<button name="deleteProduct" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete product; da:Ja, slet produkt}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveProduct" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
</gui>';
In2iGui::render($gui);
?>