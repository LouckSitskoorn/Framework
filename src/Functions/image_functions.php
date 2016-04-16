<?
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDE functions
  include_once __DIR__ . "/debug_functions.php";
  include_once __DIR__ . "/string_functions.php";
  include_once __DIR__ . "/file_functions.php";

  function saveThumbnail($originalfilename, $thumbnailfilename, $destwidth=128, $destheight=128) {
    $filepath = filename_path($thumbnailfilename);
    $extension = filename_extension($thumbnailfilename);

    if (!file_exists($filepath)) {
      mkpath($filepath, 0755);
    }

    if (file_exists($originalfilename)) {
      if($extension=="png"){
        $im=imagecreatefrompng($originalfilename);
        $srcwidth=imagesx($im);              // Original picture width is stored
        $srcheight=imagesy($im);             // Original picture height is stored
        $newimage=imagecreatetruecolor($destwidth,$destheight);
        imagecopyresized($newimage,$im,0,0,0,0,$destwidth,$destheight,$srcwidth,$srcheight);
        imagepng($newimage,$thumbnailfilename);
        //chmod("$tsrc",0755);
      }

      if($extension=="jpg"){
        $im=imagecreatefromjpeg($originalfilename);
        $srcwidth=imagesx($im);              // Original picture width is stored
        $srcheight=imagesy($im);             // Original picture height is stored
        $newimage=imagecreatetruecolor($destwidth,$destheight);
        imagecopyresized($newimage,$im,0,0,0,0,$destwidth,$destheight,$srcwidth,$srcheight);
        imagejpeg($newimage,$thumbnailfilename);
        //chmod("$tsrc",0755);
      }
    }
  }

?>