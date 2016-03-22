<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Images
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ImageTransformationService {
  
  static function loadImage($path,$ext=null) {
    if (file_exists($path) && is_readable($path)) {
      if ($ext==null) {
        $ext = FileSystemService::getFileExtension($path);
        $ext = strtolower($ext);
      }
      if (!$ext) {
        $info = ImageTransformationService::getImageInfo($path);
        if ($info['mime']=='image/png') {
          $ext = 'png';
        } else if ($info['mime']=='image/jpeg') {
          $ext = 'jpg';
        } else if ($info['mime']=='image/gif') {
          $ext = 'gif';
        }
      }
      // create an image of the given filetype
      if ($ext=="jpg" || $ext == "jpeg") {
        if ($image = @ImageCreateFromJpeg($path)) {
          return $image;
        }
      }
      elseif ($ext=="png") {
        if ($image = @ImageCreateFromPng($path)) {
          return $image;
        }
      }
      elseif ($ext=="gif") {
        if ($image = @ImageCreateFromGif($path)) {
          return $image;
        }
      }
    }
    return null;
  }
  
  static function getImageInfo($path) {
    if (!file_exists($path)) {
      return null;
    }
    $size = getimagesize($path);
    if ($size[0]===null) {
      return null;
    }
    return array('width'=>$size[0],'height'=>$size[1],'mime'=>$size['mime']);
  }
  
  static function fitInside($size,$box) {
    // If the width is one to focus on
    if ($size['width']/$size['height'] > $box['width']/$box['height']) {
      $width = $box['width'];
      $height = round($size['height']*($box['width']/$size['width']));
    }
    else if ($size['width']/$size['height'] <= $box['width']/$box['height']) {
      $height = $box['height'];
      $width = round($size['width']*($box['height']/$size['height']));
    }
    return array('width'=>$width,'height'=>$height);
  }
  
  static function cropInside($size,$box) {
    $sizeRatio = $size['width'] / $size['height'];
    $boxRatio = $box['width'] / $box['height'];
    $top = 0;
    $left = 0;
    // If the size is more "wide" than the box
    if ($sizeRatio > $boxRatio) {
      //$left=0;
      $width = $size['height'] * $box['width'] / $box['height'];
      $height = $size['height'];
    } else {
      $width = $size['width'];
      $height = round( $size['width'] * $box['height'] / $box['width'] );
    }
    $left = round(($size['width']-$width) / 2);
    $top = round(($size['height']-$height) / 2);
    return array('top' => $top, 'left' => $left, 'width' => $width, 'height' => $height);
  }

  static function blur(&$image,$level=1) {
    if (true) {
      for ($i=0; $i < $level; $i++) { 
        imagefilter($image,IMG_FILTER_GAUSSIAN_BLUR);
      }
    } else {
      $x = 1;
      $gaussian = array(array($x*1.0, $x*2.0, $x*1.0), array($x*2.0, $x*4.0, $x*2.0), array($x*1.0, $x*2.0, $x*1.0));
      ImageConvolution($image, $gaussian, 16, 0);
    }
  }
  
  static function sharpen(&$image) {
    $sharpenMatrix = array(array(-1,-1,-1),array(-1,16,-1),array(-1,-1,-1));
    $divisor = 8;
    $offset = 0;
    imageconvolution($image, $sharpenMatrix, $divisor, $offset);
  }
  
  static function applyFilter($image,$filter,$width,$height) {
    if ($filter['name']=='blur') {
      ImageTransformationService::blur($image,$filter['amount']);
    }
    else if ($filter['name']=='sharpen') {
      ImageTransformationService::sharpen($image);
    }
    else if ($filter['name']=='greyscale') {
      imagefilter($image,IMG_FILTER_GRAYSCALE);     
    }
    else if ($filter['name']=='contrast') {
      imagefilter($image,IMG_FILTER_CONTRAST,$filter['amount']);
    }
    else if ($filter['name']=='brightness') {
      imagefilter($image,IMG_FILTER_BRIGHTNESS,$filter['amount']);
    }
    else if ($filter['name']=='border') {
      for ($i=0; $i < $filter['width']; $i++) { 
        imagerectangle($image,$i,$i,$width-$i,$height-$i,imagecolorallocate($image,255,255,255));
      }
    }
  }
  
  static function transform($recipe) {
    $image = ImageTransformationService::loadImage($recipe['path']);
    if ($image==null) {
      Log::warn('Unable to load image: '.$recipe['path']);
      return;
    }
    $originalInfo = ImageTransformationService::getImageInfo($recipe['path']);
    if (isset($recipe['width']) || isset($recipe['height']) || isset($recipe['scale'])) {
      $originalWidth = $originalInfo['width'];
      $originalHeight = $originalInfo['height'];
      $finalWidth = @$recipe['width'];
      $finalHeight = @$recipe['height'];
      $scale = @$recipe['scale'];
      $left = 0;
      $top = 0;
      if ($scale) {
        //Log::debug($recipe);
        $finalWidth = round($originalInfo['width'] * $scale / 100);
        $finalHeight = round($originalInfo['height'] * $scale / 100);
        //Log::debug(array($originalInfo,$finalWidth,$finalHeight));
      } else {
        if ($finalWidth==null) {
          $finalWidth = round($originalInfo['width']/$originalInfo['height']*$finalHeight);
        }
        if ($finalHeight==null) {
          $finalHeight = round($originalInfo['height']/$originalInfo['width']*$finalWidth);
        }
        if (@$recipe['method']=='stretch') {
          // noop
        } else if (@$recipe['method']=='fit') {
          $finalSize = ImageTransformationService::fitInside($originalInfo,array('width'=>$finalWidth,'height'=>$finalHeight));
          $finalWidth = $finalSize['width'];
          $finalHeight = $finalSize['height'];
        } else if (@$recipe['method']=='crop') {
          $pos = ImageTransformationService::cropInside($originalInfo,array('width'=>$finalWidth,'height'=>$finalHeight));
          $left = $pos['left'];
          $top = $pos['top'];
          $originalWidth = $pos['width'];
          $originalHeight = $pos['height'];
        }
      }
      // TODO Maybe detect if memory will get exhausted
      if (false && !ImageTransformationService::_memoryCheck($finalWidth, $finalHeight)) {
        Log::debug('Not enough memory: width='.$finalWidth.',height='.$finalHeight);
        Log::debug($recipe);
        return;
      }
      $thumb = @imagecreatetruecolor ($finalWidth, $finalHeight);
      $white = @imagecolorallocate($thumb, 255, 255, 255);
      @imagefill($thumb,0,0,$white);
      @ImageCopyResampled($thumb, $image, 0, 0, $left, $top, $finalWidth, $finalHeight, $originalWidth, $originalHeight);
      @imagedestroy($image);
      $image = $thumb;
    }
    // TODO: make filters an array so that they can be combined in different sequences
    if (isset($recipe['filters']) && is_array($recipe['filters'])) {
      $filters = $recipe['filters'];
      foreach ($filters as $filter) {
        ImageTransformationService::applyFilter($image,$filter,$finalWidth, $finalHeight);
      }
    }
    if (isset($recipe['format'])) {
      $format = $recipe['format'];
    } else if (isset($recipe['destination'])) {
      $format = FileSystemService::getFileExtension($recipe['destination']);
    } else {
      $format = FileService::mimeTypeToExtension($originalInfo['mime']);
    }
    if (!isset($recipe['destination'])) {
      //header("Content-Length: " . filesize($basePath.$cache));
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($recipe['path'])).' GMT');
      header('Expires: '.gmdate('D, d M Y H:i:s',time()+(60*60)) . ' GMT');
      header('Date: '.gmdate('D, d M Y H:i:s') . ' GMT');
      header('Content-Type: '.FileService::extensionToMimeType($format));
    }
    if ($format=='png') {
      @imagepng($image,$recipe['destination']);
    } else if ($format=='gif') {
      @imagegif($image,$recipe['destination']);
    } else {
      //imageinterlace($image,1); TODO Maybe interlace JPEGs
      if (isset($recipe['quality'])) {
        @imagejpeg($image,$recipe['destination'],$recipe['quality']);
      } else {
        @imagejpeg($image,$recipe['destination']);
      }
    }
    if (isset($recipe['destination'])) {
      ImageTransformationService::optimizeFile($recipe['destination']);
    }
    @imagedestroy($image);
  }
    
  static function _memoryCheck ($x, $y, $rgb=3) {
    $maxmem = 32*1024*1024;
    return ( $x * $y * $rgb * 1.7 < $maxmem - memory_get_usage() );
  }
    
  static function optimizeFile($file) {
    return;
    $output = [];
    // TODO escape quote
    exec('optipng -o7 "'.$file.'"',$output);
    exec('jpegoptim "'.$file.'"',$output);
    Log::debug($output);
  }
      
  static function sendFile($path,$mimeType) {
    if (!file_exists($path)) {
      error_log('Cannot send image, path does not exist: '.$path);
      return;
    }
    if (!is_readable($path)) {
      error_log('Cannot send image, path is not readable: '.$path);
      return;
    }
    if (!$mimeType) {
      if ($info = ImageTransformationService::getImageInfo($path)) {
        $mimeType = $info['mime'];
      }
    }
    if ($mimeType=='png') {
      $mimeType='image/png';
    } else if ($mimeType=='jpg') {
      $mimeType='image/jpeg';
    } else if ($mimeType=='gif') {
      $mimeType='image/gif';
    }
    header('Content-Type: '.$mimeType);
    header("Content-Length: ".filesize($path));
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($path)).' GMT');
    header('Pragma: public');
    header("Cache-Control: public");
    header('Expires: '.gmdate('D, d M Y H:i:s',time()+(7*24*60*60)) . ' GMT');
    header('Date: '.gmdate('D, d M Y H:i:s') . ' GMT');
    readfile($path);
  }
  
  static function buildCachePath($id,$recipe) {
    global $basePath;
    $path = $basePath.'local/cache/images/'.$id;
    if ($recipe['width']) {
      $path.='_width-'.$recipe['width'];
    }
    if ($recipe['height']) {
      $path.='_height-'.$recipe['height'];
    }
    if ($recipe['scale']) {
      $path.='_scale-'.$recipe['scale'];
    }
    if ($recipe['method']) {
      $path.='_method-'.$recipe['method'];
    }
    if ($recipe['quality']) {
      $path.='_quality-'.$recipe['quality'];
    }
    foreach ($recipe['filters'] as $filter) {
      $path.='_'.$filter['name'];
      if (isset($filter['amount'])) {
        $path.='-'.$filter['amount'];
      }
      if (isset($filter['width'])) {
        $path.='-'.$filter['width'];
      }
    }
    if ($recipe['format']) {
      $path.='.'.$recipe['format'];
    }
    return $path;
  }
}