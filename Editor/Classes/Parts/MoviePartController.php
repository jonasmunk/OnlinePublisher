<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class MoviePartController extends PartController
{
	function MoviePartController() {
		parent::PartController('movie');
	}
	
	function createPart() {
		$part = new MoviePart();
        $part->setHeight('300px');
        $part->setWidth('100%');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return '<div id="part_movie_container">'.$this->render($part,$context).'</div>'.

        $this->buildHiddenFields([
			'fileId' => $part->getFileId()>0 ? $part->getFileId() : '',
			'imageId' => $part->getImageId()>0 ? $part->getImageId() : '',
			'text' => $part->getText(),
			'url' => $part->getUrl(),
			'code' => $part->getCode(),
			'movieWidth' => $part->getWidth(),
			'movieHeight' => $part->getHeight()
        ]).
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/movie/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$part = MoviePart::load($id);
		$part->setFileId(Request::getInt('fileId'));
		$part->setImageId(Request::getInt('imageId'));
		$part->setText(Request::getString('text'));
		$part->setUrl(Request::getString('url'));
		$part->setCode(Request::getString('code'));
		$part->setWidth(Request::getString('movieWidth'));
		$part->setHeight(Request::getString('movieHeight'));
        Log::debug($part);
		return $part;
	}
	
	function buildSub($part,$context) {
		$xml='<movie xmlns="'.$this->getNamespace().'">';
		$xml.='<style';
        if (Strings::isNotBlank($part->getWidth())) {
            $xml.=' width="'.Strings::escapeXML($part->getWidth()).'"';
        }
        if (Strings::isNotBlank($part->getHeight())) {
            $xml.=' height="'.Strings::escapeXML($part->getHeight()).'"';
        }
        $xml.='/>';
		if (Strings::isNotBlank($part->getText())) {
			$xml.='<text>' . Strings::escapeXML($part->getText()) . '</text>';
		}
		if (Strings::isNotBlank($part->getUrl())) {
			$xml.='<url>' . Strings::escapeXML($part->getUrl()) . '</url>';
		}
		if (Strings::isNotBlank($part->getCode())) {
			$xml.='<code><![CDATA[' . $part->getCode() . ']]></code>';
		}
        if ($part->getImageId() > 0) {
            $xml.='<image id="' . intval($part->getImageId()) . '"/>';
        }
        if ($part->getFileId() > 0) {
            $xml.='<file id="' . intval($part->getFileId()) . '"/>';
        }
        if ($part->getImageId() > 0) {
            if ($image = ObjectService::getObjectData($part->getImageId())) {
                $xml.= '<poster>' . $image . '</poster>';
            }
        }
		if (Strings::isNotBlank($part->getCode())) {
			$xml.='<source type="code"><![CDATA[' . $part->getCode() . ']]></source>';
		}
		$analyzed = Strings::analyzeMovieURL($part->getUrl());
		if ($analyzed) {
			$xml.='<source type="' . $analyzed['type'] . '" id="' . $analyzed['id'] . '"/>';
		}
		$sql="select object.data,file.type from object,file where file.object_id = object.id and object.id=".Database::int($part->getFileId());
		if ($row = Database::selectFirst($sql)) {
			$xml.='<source type="file">';
			$xml.=$row['data'];
			$xml.='</source>';
		}
		$xml.='</movie>';
		return $xml;
	}
	
	function importSub($node,$part) {
		if ($file = DOMUtils::getFirstDescendant($node,'file')) {
			if ($id = intval($file->getAttribute('id'))) {
				$part->setFileId($id);
			}
		}
		if ($image = DOMUtils::getFirstDescendant($node,'image')) {
			if ($id = intval($image->getAttribute('id'))) {
				$part->setImageId($id);
			}
		}
		if ($style = DOMUtils::getFirstDescendant($node,'style')) {
			$part->setWidth($style->getAttribute('width'));
			$part->setHeight($style->getAttribute('height'));
		}
		if ($text = DOMUtils::getFirstDescendant($node,'text')) {
			$part->setText(DOMUtils::getText($text));
		}
		if ($url = DOMUtils::getFirstDescendant($node,'url')) {
			$part->setUrl(DOMUtils::getText($url));
		}
		if ($code = DOMUtils::getFirstDescendant($node,'code')) {
			$part->setCode(DOMUtils::getText($code));
		}
	}
	
	
	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Movie','da'=>'Film')) =>
			'<script source="../../Parts/movie/toolbar.js"/>
			<icon icon="common/new" title="{Add file; da:Tilføj fil}" name="addFile"/>
			<icon icon="common/search" title="{Select file; da:Vælg fil}" name="chooseFile"/>
			<divider/>
			<icon icon="common/info" title="{Info; da:Info}" name="info"/>
		'
		);
	}

	function editorGui($part,$context) {
		$gui='
		<window title="{Add file; da:Tilføj fil}" name="fileUploadWindow" width="300" padding="10">
			<upload name="fileUpload" url="../../Parts/file/Upload.php" widget="upload">
				<placeholder 
					title="{Select a file on your computer; da:Vælg en fil på din computer...}" 
					text="{The file size can at most be; da:Filens størrelse må højest være} '.GuiUtils::bytesToString(FileSystemService::getMaxUploadSize()).'."
                />
			</upload>
			<buttons align="center" top="10">
				<button name="cancelUpload" title="{Close; da:Luk}"/>
				<button name="upload" title="{Select file...; da:Vælg fil...}" highlighted="true"/>
			</buttons>
		</window>

		<window title="{Info; da:Info}" icon="common/info" name="movieInfoWindow" width="300" padding="10">
            <formula name="movieInfoFormula">
                <fields>
                    <field label="Poster">
                        <image-input key="image">
                            <finder url="../../Services/Finder/Images.php"/>
                        </image-input>
                    </field>
                    <field label="Movie">
                        <object-input key="file">
                            <finder url="../../Services/Finder/Files.php"/>
                        </object-input>
                    </field>
                    <field label="Address"><text-input key="url"/></field>
                    <field label="Code"><text-input breaks="true" key="code"/></field>
                    <field label="Width"><style-length-input key="movieWidth"/></field>
                    <field label="Height"><style-length-input key="movieHeight"/></field>
                    <field label="Text"><text-input breaks="true" key="text"/></field>
                </fields>
            </formula>
		</window>
		';
		return UI::renderFragment($gui);
	}
	
	function setLatestUploadId($id) {
		$_SESSION['part.movie.latest_upload_id'] = $id;
	}
	
	function getLatestUploadId() {
		return $_SESSION['part.movie.latest_upload_id'];
	}	
}
?>