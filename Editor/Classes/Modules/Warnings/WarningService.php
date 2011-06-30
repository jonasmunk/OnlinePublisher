<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Warnings
 */
require_once($basePath.'Editor/Classes/Modules/Warnings/Warning.php');

class WarningService {
	
	function getWarnings() {
		$warnings = array();
		
		$sql = "select title,id from page where description='' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$warning = new Warning();
			$warning->setText('Siden har ingen beskrivelse');
			$warning->setEntity(array('type'=>'page','title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/page'));
			//$problem->addAction('Rediger','Tools/Pages/?action=pageproperties&amp;id='.$row['id'],'Desktop');
			$warnings[] = $warning;
		}
		Database::free($result);
		
		return $warnings;
	}
	
}