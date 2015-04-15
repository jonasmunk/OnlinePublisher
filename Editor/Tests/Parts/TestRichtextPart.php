<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestRichtextPart extends UnitTestCase {
    
  function testLoad() {
    $this->assertNull(RichtextPart::load(0));
  }

  function testCreate() {
    $obj = new RichtextPart();
    $this->assertFalse($obj->isPersistent());
    $obj->save();
    $this->assertTrue($obj->isPersistent());
    $id = $obj->getId();
    $this->assertNotNull(RichtextPart::load($id));
    $obj->remove();
    $this->assertNull(RichtextPart::load($id));
  }

  function testProperties() {
    $obj = new RichtextPart();
    $obj->setHtml('<h1>Test</h1>');
    $obj->save();
    
    $obj2 = RichtextPart::load($obj->getId());
    $this->assertEqual($obj2->getHtml(),'<h1>Test</h1>');
    
    $obj2->remove();
  }
  

  function testDisplay() {
    $obj = new RichtextPart();
    $obj->setHtml('<h1>Please get me back!</h1>');
    $ctrl = new RichtextPartController();
    
    $html = $ctrl->display($obj,new PartContext());
    $this->assertEqual(trim($html),'<div xmlns="http://www.w3.org/1999/xhtml" class="part_richtext common_font"><h1>Please get me back!</h1></div>');
  }

  function testImportValid() {
    $obj = new RichtextPart();
    $obj->setHtml('<h1>Please get me back!</h1>');
    $ctrl = new RichtextPartController();
    
    $xml = $ctrl->build($obj,new PartContext());
    
    $this->assertNull($ctrl->importFromString(null));
    
    $imported = $ctrl->importFromString($xml);
    
    $this->assertNotNull($imported);
    $this->assertIdentical($imported->getHtml(),$obj->getHtml());
  }

  function testImportInvalid() {
    $obj = new RichtextPart();
    $obj->setHtml('Im in<alid<<>><');
    $ctrl = new RichtextPartController();
    
    $xml = $ctrl->build($obj,new PartContext());
    
    $this->assertNull($ctrl->importFromString(null));
    
    $imported = $ctrl->importFromString($xml);
    
    $this->assertNotNull($imported);
    $this->assertIdentical('Im in<alid>&gt;</alid>',$imported->getHtml());
  }

  function testLinkSynchronization() {
    $html = '<p><a data="{&quot;page&quot;:&quot;14312431&quot;}">My <span>link</span></a></p>';
    $part = new RichtextPart();
    $part->setHtml($html);
    Log::debug('-------------------');
    $part->save();
    
    $links = LinkService::getPartLinks($part->getId());
    $this->assertEqual(1,count($links));
    
    //echo $part->getHtml() . "\n";
    
    $link = $links[0];
    
    // Check that the text is correct
    $this->assertTrue($link->getId() > 0);
    $this->assertEqual('My link',$link->getSourceText());
    $this->assertEqual('page',$link->getTargetType());
    $this->assertEqual(14312431,$link->getTargetValue());
    
    // Check that the ID is stored in the data
    $dataPart = '&quot;id&quot;:' . $link->getId();
    $this->assertTrue(strpos($part->getHtml(),$dataPart)!==false);
    
    // TODO Check that removing the link in the markup will delete the link object
        
        
    $part = new RichtextPart();
    $part->setHtml('<p>Empty</p>');
        $part->save();
        
    $links = LinkService::getPartLinks($part->getId());
    $this->assertEqual(0,count($links));
        
    $part->remove();
    $this->assertNull(RichtextPart::load($part->getId()));
  }
  
  function testUnicode() {
    $html = '<p>This is unicode æøå</p>';
    $htmlOutput = '<p>This is unicode &#xE6;&#xF8;&#xE5;</p>';

    $obj = new RichtextPart();
    $obj->setHtml($html);
    $ctrl = new RichtextPartController();
    
    $converted = $ctrl->_convert($html);
    $this->assertEqual($htmlOutput,$converted);
  
    $output = $ctrl->build($obj,new PartContext());

    $expected = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="richtext" id=""><sub><richtext xmlns="http://uri.in2isoft.com/onlinepublisher/part/richtext/1.0/" valid="true">' . $htmlOutput . '</richtext></sub></part>';
    $this->assertEqual($expected,$output);
  }

  function testLinkConversion() {
    $tests = [
      // Pages
      '<p><a data="{&quot;page&quot;:&quot;14312431431&quot;}">My link</a></p>' =>
      '<p><link page="14312431431" data="{&quot;page&quot;:&quot;14312431431&quot;}">My link</link></p>'
      ,
      '<p><a data="{&quot;page&quot;:&quot;&quot;}">My link</a></p>' =>
      '<p><link data="{&quot;page&quot;:&quot;&quot;}">My link</link></p>'
      ,
      '<p><a data="{&quot;page&quot;:&quot;text&quot;}">My link</a></p>' =>
      '<p><link data="{&quot;page&quot;:&quot;text&quot;}">My link</link></p>'
      ,
      '<p><a data="{&quot;page&quot;:&quot;-1&quot;}">My link</a></p>' =>
      '<p><link data="{&quot;page&quot;:&quot;-1&quot;}">My link</link></p>'
      ,
       // Files
      '<p><a data="{&quot;file&quot;:&quot;1&quot;}">My link</a></p>' =>
      '<p><link file="1" data="{&quot;file&quot;:&quot;1&quot;}">My link</link></p>'
      ,
      // Images
      '<p><a data="{&quot;image&quot;:&quot;1&quot;}">My link</a></p>' =>
      '<p><link image="1" data="{&quot;image&quot;:&quot;1&quot;}">My link</link></p>'
      ,
      // URLs
      '<p><a data="{&quot;url&quot;:&quot;http://www.humanise.dk/?test=value&quot;}">My link</a></p>' =>
      '<p><link url="http://www.humanise.dk/?test=value" data="{&quot;url&quot;:&quot;http://www.humanise.dk/?test=value&quot;}">My link</link></p>'
      ,
      // E-mails
      '<p><a data="{&quot;email&quot;:&quot;name@domain.com&quot;}">My link</a></p>' =>
      '<p><link email="name@domain.com" data="{&quot;email&quot;:&quot;name@domain.com&quot;}">My link</link></p>'
      ,
      '<p><a><span>My link</span></a></p>' =>
      '<p><link data=""><span>My link</span></link></p>'
      ,
      '<p><a><span><a>My link</span></p>' => // TODO: maybe this should be cleaned
      '<p><link data=""><span><link data="">My link</link></span></link></p>'
    ];
    foreach ($tests as $html => $xml) {
      $obj = new RichtextPart();
      $obj->setHtml($html);
      $ctrl = new RichtextPartController();
    
      $output = $ctrl->build($obj,new PartContext());
      $expected = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="richtext" id=""><sub><richtext xmlns="http://uri.in2isoft.com/onlinepublisher/part/richtext/1.0/" valid="true">' . $xml . '</richtext></sub></part>';
      $this->assertEqual($expected,$output);
    }
  }
}
?>