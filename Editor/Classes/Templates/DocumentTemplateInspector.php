<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DocumentTemplateInspector implements Inspector {

  function inspect() {
    $inspections = [];
    $sql = "select page.id,page.title from page,document where page.id=document.page_id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $problems = DocumentTemplateEditor::check($row['id']);
			$entity = array('type'=>'page','title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/page');
			$inspection = new Inspection();
			$inspection->setCategory('content');
			$inspection->setEntity($entity);
      if (count($problems) > 0) {
				$inspection->setStatus('error');
				$inspection->setText('The document structure is broken');
        $inspection->setInfo(implode("\n", $problems));
      } else {
        $inspection->setStatus('ok');
				$inspection->setText('The document structure looks ok');
      }
			$inspections[] = $inspection;
    }
    Database::free($result);
    return $inspections;
  }

}