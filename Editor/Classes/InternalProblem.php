<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class InternalProblem {
	
	var $title;
	var $entity;
	var $actions;
	
	function InternalProblem($title) {
		$this->title = $title;
		$this->actions = array();
	}
	
	function getTitle() {
		return $this->title;
	}
	
	function setEntity($type,$title,$id) {
		$this->entity = array('type' => $type, 'title' => $title, 'id' => $id);
	}
	
	function getEntity() {
		return $this->entity;
	}
	
	function addAction($title,$link,$target) {
		$this->actions[] = array('title' => $title, 'link' => $link, 'target' => $target);
	}
	
	function getActions() {
		return $this->actions;
	}
	
	///////////////////// Static ///////////////////
	/**
	 * @static
	 */
	function findProblems() {
		$out = array();
		$sql = "select distinct page.id,page.title from page,document_section where type != 'part' and page.id = document_section.page_id order by page.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$problem = new InternalProblem("Siden indeholder gamle afsnit");
			$problem->setEntity('page',$row['title'],$row['id']);
			$problem->addAction('Rediger','Template/Edit.php?id='.$row['id'],'Desktop');
			$out[] = $problem;
		}
		Database::free($result);
		
		$sql = "select * from page where description='' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$problem = new InternalProblem("Siden har ingen beskrivelse");
			$problem->setEntity('page',$row['title'],$row['id']);
			$problem->addAction('Rediger','Tools/Pages/?action=pageproperties&amp;id='.$row['id'],'Desktop');
			$out[] = $problem;
		}
		Database::free($result);
		
		return $out;
	}
}
?>