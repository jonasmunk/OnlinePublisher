<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Part::$schema['mailinglist'] = array(
	'fields' => array(
	),
	'relations' => array(
		'mailinglistIds' => array( 'table' => 'part_mailinglist_mailinglist', 'fromColumn' => 'part_id', 'toColumn' => 'mailinglist_id' )
	)
);

class MailinglistPart extends Part
{
	var $mailinglistIds;
	
	function MailinglistPart() {
		parent::Part('mailinglist');
	}
	
	static function load($id) {
		return Part::get('mailinglist',$id);
	}
	
	function setMailinglistIds($mailinglistIds) {
	    $this->mailinglistIds = $mailinglistIds;
	}

	function getMailinglistIds() {
	    return $this->mailinglistIds;
	}
	
}
?>