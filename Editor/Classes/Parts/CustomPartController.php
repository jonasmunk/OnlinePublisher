<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class CustomPartController extends PartController
{
	function CustomPartController() {
		parent::PartController('custom');
	}

	static function createPart() {
		$part = new CustomPart();
		$part->save();
		return $part;
	}

	function display($part,$context) {
		return $this->render($part,$context);
	}

	function editor($part,$context) {
		return '<div id="part_custom_container">'.$this->render($part,$context).'</div>'.

    $this->buildHiddenFields([
			'workflowId' => $part->getWorkflowId(),
			'viewId' => $part->getViewId()
    ]) .
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/custom/editor.js" type="text/javascript" charset="utf-8"></script>';
	}

	function getFromRequest($id) {
		$part = CustomPart::load($id);
		$part->setWorkflowId(Request::getInt('workflowId'));
		$part->setViewId(Request::getInt('viewId'));
		return $part;
	}

	function isDynamic($part) {
		return true;
	}

	function buildSub($part,$context) {
		$xml='<custom xmlns="'.$this->getNamespace().'">';
    $workflow = Workflow::load($part->getWorkflowId());
    $view = View::load($part->getViewId());
    if ($workflow && $view) {
      $parser = new WorkflowParser();
      $desc = $parser->parse($workflow->getRecipe());
      if ($desc) {
        $result = $desc->run();
        $path = $view->getPath();

        $rendered = RenderingService::applyTwigTemplate([
          'path' => FileSystemService::join($path, 'template.twig'),
          'variables' => ['data' => $result]
        ]);
        $inlineCSS = FileSystemService::join($path, 'inline.css');
        $asyncCSS = FileSystemService::join($path, 'async.css');
        $xml.= '<css xmlns="http://uri.in2isoft.com/onlinepublisher/resource/"';
        if (FileSystemService::canRead($inlineCSS)) {
          $xml.= ' inline="' . Strings::escapeXML($inlineCSS) . '"';
        }
        if (FileSystemService::canRead($asyncCSS)) {
          $xml.= ' async="' . Strings::escapeXML($asyncCSS) . '"';
        }
        $xml.= '/>';
        $xml.= '<rendered xmlns="http://www.w3.org/1999/xhtml">' . $rendered . '</rendered>';
      }
    }
		$xml.='</custom>';
		return $xml;
	}

	function importSub($node,$part) {
/*		if ($object = DOMUtils::getFirstDescendant($node,'object')) {
			if ($id = intval($object->getAttribute('id'))) {
				$part->setFileId($id);
			}
		}
		if ($text = DOMUtils::getFirstDescendant($node,'text')) {
			$part->setText(DOMUtils::getText($text));
		}*/
	}


	function getToolbars() {
		return array(
			GuiUtils::getTranslated(['Custom','da'=>'Speciel']) =>
			'<script source="../../Parts/custom/toolbar.js"/>
			<field label="{Workflow; da:Arbejdsgang}">
				<dropdown name="workflow" width="200">'.GuiUtils::buildObjectItems('workflow').'</dropdown>
			</field>
			<field label="{View; da:Visning}">
				<dropdown name="view" width="200">'.GuiUtils::buildObjectItems('view').'</dropdown>
			</field>
		'
		);
	}



	function editorGui($part,$context) {
		$gui='
		<window title="{Add file; da:Tilføj fil}" name="customPartWindow" width="300" padding="10">
			<buttons align="center" top="10">
				<button name="cancelUpload" title="{Close; da:Luk}"/>
				<button name="upload" title="{Select file...; da:Vælg fil...}" highlighted="true"/>
			</buttons>
		</window>
		';
		return UI::renderFragment($gui);
	}
}
?>