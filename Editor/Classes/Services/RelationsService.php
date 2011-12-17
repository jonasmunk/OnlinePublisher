<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class RelationsService {
	
	function relateObjectToObject($fromObject,$toObject,$kind='') {
		if (!$fromObject || !$toObject) {
			return false;
		}
		$sql = array(
			'table' => 'relation',
			'values' => array(
				'from_type' => Database::text('object'),
				'from_object_id' => Database::int($fromObject->getId()),
				'to_type' => Database::text('object'),
				'to_object_id' => Database::int($toObject->getId()),
				'kind' => Database::text($kind)
			)
		);
		Database::insert($sql);
		return true;
	}
	
	function relatePageToObject($fromPage,$toObject,$kind='') {
		if (!$fromPage || !$toObject) {
			return false;
		}
		$sql = array(
			'table' => 'relation',
			'values' => array(
				'from_type' => Database::text('page'),
				'from_object_id' => Database::int($fromPage->getId()),
				'to_type' => Database::text('object'),
				'to_object_id' => Database::int($toObject->getId()),
				'kind' => Database::text($kind)
			)
		);
		Database::insert($sql);
		return true;
	}
	
	function relateObjectToPage($fromObject,$toPage,$kind='') {
		if (!$fromObject || !$toPage) {
			return false;
		}
		$sql = array(
			'table' => 'relation',
			'values' => array(
				'from_type' => Database::text('object'),
				'from_object_id' => Database::int($fromObject->getId()),
				'to_type' => Database::text('page'),
				'to_object_id' => Database::int($toPage->getId()),
				'kind' => Database::text($kind)
			)
		);
		Database::insert($sql);
		return true;
	}
}