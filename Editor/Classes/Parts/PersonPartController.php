<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ImagegalleryPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PersonPartController extends PartController
{
	function PersonPartController() {
		parent::PartController('person');
	}
	
	function getToolbars() {
		return array('Person' => '
			<script source="../../Parts/person/toolbar.js"/>
			<segmented label="Placering" name="alignment" allow-null="true">
				<item icon="style/align_left" value="left"/>
				<item icon="style/align_center" value="center"/>
				<item icon="style/align_right" value="right"/>
			</segmented>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showFirstName"/><label>Fornavn</label></cell>
					<cell right="5"><checkbox name="showMiddleName"/><label>Mellemnavn</label></cell>
					<cell right="5"><checkbox name="showSurname"/><label>Efternavn</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showInitials"/><label>Initialer</label></cell>
					<cell right="5"><checkbox name="showNickname"/><label>Kaldenavn</label></cell>
					<cell right="5"><checkbox name="showSex"/><label>Koen</label></cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showImage"/><label>Billede</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showWebaddress"/><label>Webadresse</label></cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showStreetname"/><label>Gade</label></cell>
					<cell right="5"><checkbox name="showCity"/><label>By</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showZipcode"/><label>Postnummer</label></cell>
					<cell right="5"><checkbox name="showCountry"/><label>Land</label></cell>
				</row>
			</grid>
			<divider/>
			<grid>
				<row>
					<cell right="5"><checkbox name="showEmailPrivate"/><label>Email (privat)</label></cell>
					<cell right="5"><checkbox name="showPhonePrivate"/><label>Telefon (privat)</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="showEmailJob"/><label>Email (job)</label></cell>
					<cell right="5"><checkbox name="showPhoneJob"/><label>Telefon (job)</label></cell>
				</row>
			</grid>
		');
	}
}