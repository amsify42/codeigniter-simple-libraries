<?php

require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

class File {

  public function exist($file) 
  {
    if(is_file($file)) {
      return true;
    } 
    return false;
  }

  public function delete($file)
  {
    if(is_file($file)) {
      unlink($file);
      return true;
    }
    return false;
  }

  public function get($var)
  {
      if(isset($_FILES[$var]) && $_FILES[$var]['name'] != '') {
        return $_FILES[$var];
      }
      return NULL;
  }

  public function getExtension($source) 
  {
    return pathinfo($source["name"], PATHINFO_EXTENSION);
  }


 // For Image
 public function uploadImage($w, $h, $x, $y, $source, $location, $max = 400, $name = '', $type = 'general', $url = 'partial')
 {

    $result             = array('status' => false, 'message' => 'something went wrong');  
    $allowedExtensions  = array('jpg','jpeg','png');

      if(is_string($source)) {
          $extension          = strtolower(pathinfo($source, PATHINFO_EXTENSION));
      } else {
          $extension          = strtolower($this->getExtension($source));
      }

    if(!in_array($extension, $allowedExtensions)) {
        $result['message']      = $extension.' is not allowed';
        $result['fileNameURL']  = ''; 
        return $result;
    }

    //var_dump($source); die;
    $makeImage = $source;
    if(is_array($source)) {
      $makeImage = $source['tmp_name'];
    }

    $image        = Image::make($makeImage);
    //dd($source);
    $mime         = $image->mime();

    $image->encode($extension);

    $publicPath   = base_path($location);
    $urlPath      = base_url($location);

    $name         = preg_replace('/\s+/', '', $name);
    $fileName     = $name.'-'.date('Y-m-d-H-i-s').'.'.$extension;
    $fileNamePath = $publicPath.'/'.$fileName;

    if($url == 'full'):
    $fileNameURL  = $urlPath.'/'.$fileName; 
    else:
    $fileNameURL  = $fileName;   
    endif; 

    if($x == 0 && $y == 0 && $w == 0 && $h == 0) {

      $size         = $this->getDynamicWidthAndHeight($makeImage, $max, $type);
      $image->resize($size['d_width'], $size['d_height']);

    } else {

      $image->crop($w, $h, $x, $y);

    }


      $image->save($fileNamePath);

      $result['status']     = true;
      $result['message']    = 'image successfully saved';
      $result['fileNameURL']  = $fileNameURL;

      return $result;
  }


  public function getDynamicWidthAndHeight($source, $maxRange, $type = 'general')
  {
      $result               = array();
      $divide               = 100;

      if($type == 'general'):
      $size                 = getimagesize($source);  
      else:
      $size                 = getimagesizefromstring($source);  
      endif;  

      if($size['0'] > $maxRange) {

          // Dynamic resizing applying  to achieve desired size
          $wby = floor($size['0']/$divide);
          $hby = floor($size['1']/$divide);
          $i   = $divide;

          while($i>=0) {
              
              $result['d_width']    = $wby*$i;
              $result['d_height']   = $hby*$i;

              if($result['d_width'] <= $maxRange)
                  break;
              $i--;
          }
        } else {

        $result['d_width']    = $size['0'];
        $result['d_height']   = $size['1'];            

        }

        return $result;
  }



  // For General File Upload
  public function uploadFile($source, $location, $name = '', $url = 'partial')
  {
      $fileNameURL  = ''; 
      $fileName     = $name;

      if($this->getExtension($source) != '') {
        $fileName   .= '.'.$this->getExtension($source);
      }
      move_uploaded_file($source['tmp_name'], $location.'/'.$fileName);
      if($url == 'full'):
      $fileNameURL  = $location.'/'.$fileName; 
      else:
      $fileNameURL  = $fileName;   
      endif;

      return $fileNameURL;
  }

}