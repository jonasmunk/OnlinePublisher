<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class FormulaPartController extends PartController
{
	function FormulaPartController() {
		parent::PartController('formula');
	}
	
	function createPart() {
		$part = new FormulaPart();
		$part->save();
		return $part;
	}
	
	function getToolbars() {
		return array(
			'Formular' => '
			<script source="../../Parts/formula/toolbar.js"/>
			<grid>
				<row>
					<cell label="Modtager:" width="180">
						<textfield adaptive="true" name="receiverName"/>
					</cell>
				</row>
				<row>
					<cell label="E-mail:" width="180">
						<textfield adaptive="true" name="receiverEmail"/>
					</cell>
				</row>
			</grid>
		');
	}
}
?>