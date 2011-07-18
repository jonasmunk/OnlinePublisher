<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Warnings
 */
require_once($basePath.'Editor/Classes/Modules/Warnings/Warning.php');

class WarningService {
	
	function getWarnings() {
		$warnings = array();
		
		$sql = "select title,id,description from page order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$warning = new Warning();
			$warning->setEntity(array('type'=>'page','title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/page'));
			if (StringUtils::isBlank($row['description'])) {
				$warning->setStatus('warning');
				$warning->setText('Siden har ingen beskrivelse');
			} else {
				$warning->setStatus('ok');
				$warning->setText('Siden har ingen kendte problemer');
			}
			$warnings[] = $warning;
		}
		Database::free($result);
		
		return $warnings;
	}
	
}