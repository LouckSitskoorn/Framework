<?
  //NAMESPACE
  namespace SB\Functions;

//include functions
  include_once __DIR__ . "/debug_functions.php";
  include_once __DIR__ . "/string_functions.php";

  //make a directory
  /*
  function mkpath($path, $gebruikersrechten=0755) {
    $dirs = array();
    $path = preg_replace('/(\/){2,}|(\\\){1,}/','/',$path); //only forward-slash
    $dirs = explode("/",$path);
    $path = "";

    foreach ($dirs as $element) {
      $path.=$element."/";

      if(!is_dir($path)) {
        $old_umask = umask(0);
        if(!mkdir($path)){
          return 0;
        } else {
          chmod($path, $gebruikersrechten);
        }
      } else {
        try {
          //chmod($path, 0755);
        } catch (Exception $e) {
        	//do nothing
        }
      }
    }
  }
  */

  function realmkpath($path, $rights=0755, $owner="") {
    if (!file_exists($path)) {
      pb($path);
      pb(normalizePath($path));
      mkpath(normalizePath($path), $rights, $owner);
    }

    return realpath($path);
  }


  function mkpath($path, $rights=0755, $owner="") {
    $returnvalue = false;

    if(!is_dir($path)
    && !is_empty($path)) {
      $old_umask    = umask(0);

      try {
        $realpath = removeDots($path);

        if (contains($path, ":")) {
          $realpath = rightpart($path, ":");
        }

        $returnvalue  = mkdir($realpath , $rights, true);

        umask($old_umask);

        //chmod($path, $rights);
      } catch  (Exception $e) {
        fbb("ERROR PATH:" .$path);
      }
    } else {
      //chmod($path, 0755);
      $returnvalue  = true;
    }

    if ($owner) {
      chown($path, $owner);
    }

    if (!$returnvalue) {
      error_logger(E_USER_ERROR, "mkpath failed", "Path:" . $path, basename(__FILE__), __LINE__);
    }

    return $returnvalue;
  }


  /**
  * Recursively delete a directory
  *
  * @param string $dir Directory name
  * @param boolean $deleteRootToo Delete specified top-level directory as well
  */
	function delpath($dir, $deleteRootToo=false) {
    if(!$dh = @opendir($dir)) {
      return;
    }

    while (false !== ($obj = readdir($dh))) {
      if (!is_empty($obj)
      && file_exists($dir . "/" . $obj) ) {

        if($obj == '.' || $obj == '..') {
          continue;
        }

        if (is_dir($dir . '/' . $obj)) {
        //if (!@unlink($dir . '/' . $obj)) {
          delpath($dir.'/'.$obj, true);
        } else {
          @unlink($dir . '/' . $obj);
        }
      }
    }

    closedir($dh);

    if ($deleteRootToo) {
      if (is_dir_empty(realpath($dir))) {
        //chmod(realpath($dir), 0755);
        @rmdir(realpath($dir));
      }
    }

    return;
  }


  /**
  * Delete all files (and ONLY files) in a directory
  *
  * @param string $dir Directory name
  */
  function delpathfiles($dir) {
    // Open the directory
    $dh = opendir($dir);

    // Loop over all of the files in the folder
    while ($file = readdir($dh)) {
      // If $file is NOT a directory remove it
      if(!is_empty($file)
      && !is_dir(trim($file))
      && !is_dir(trim($dir) . "/" . trim($file))) {
        unlink (trim($dir) . "/" . trim($file)); // unlink() deletes the files
      }
    }

    // Close the directory
    closedir($dh);
  }

  function delpathfiles_match($path, $match="*", $recursive=false){
    static $deld = 0, $dsize = 0;

    $dirs = glob($path."*");
    $files = glob(striplastslash($path) . "/" . $match);
    foreach($files as $file){
      if(is_file($file)){
         $dsize += filesize($file);
         unlink($file);
         $deld++;
      }
    }

    if ($recursive) {
      foreach($dirs as $dir){
        if(is_dir($dir)){
           $dir = basename($dir) . "/";
           delpathfiles_match($path.$dir, $match, $recursive);
        }
      }
    }

    //return "$deld files deleted with a total size of $dsize bytes";
  }


  /**
   * Delete all files in a path older than $days
   *
   * @param string $dir       Directory name
   * @param array  $subdirs   Subdirectories
   * @param number $days      Amount of days
  */
  function delpathfiles_days($dir, $subdirs=[], $days=0, $deleteemptydir=false, $delete=false) {
    foreach(glob("{$dir}/*") as $file) {
      $filenopath = filename_nopath($file);

      if(is_dir($file)) {
        delpathfiles_days($file, $subdirs, $days, $deleteemptydir, $delete || in_array($filenopath, $subdirs));

        if (is_dir_empty($file)) {
          rmdir($file);
        }
      } else {
        if ($delete) {
          if (time() - filemtime($file) >= 60*60*24*$days) {
            unlink($file);
            //printbreak($file);
          }
        }
      }
    }
  }


  /**
  * Check if directory exists
  *
  * @param string $dir  Directory name
  * @param string $path Path
  */
  /*
  function dir_exists($dir = false, $path = '../') {
     if(!$dir) {
       return false;
     }
     if(is_dir($path.$dir)) {
       return true;
     }
     $tree = glob($path.'*', GLOB_ONLYDIR);
     if($tree && count($tree) > 0) {
       foreach($tree as $dir) {
         if(dir_exists($dir, $dir.'/')) {
           return true;
         }
       }
     }

     return false;
  }
  */

  function getfile($filefull) {
    if (!is_empty($filefull)) {
      if (file_exists($filefull)) {
        return file_get_contents($filefull);
      } else {
        return false;
      }
    }
  }


  function writefile($filefull, $text) {
    $returnvalue = false;

    if (!is_empty($filefull)) {
      $filepath = filename_path($filefull);
      if (!file_exists($filepath)) {
        mkpath($filepath);
      }

      $filehandle = fopen($filefull, "w+");

      if (is_writable($filefull)) {
        fwrite($filehandle, $text);
        fclose($filehandle);

        $returnvalue = true;
      }
    }

    return $returnvalue;
  }


  function appendfile($filefull, $text) {
    $returnvalue = false;

    if (!is_empty($filefull)) {
      $filepath = realpath(filename_path($filefull));
      if (!file_exists($filepath)) {
        mkpath($filepath);
      }

      $filehandle = fopen($filefull, "a+");

      if (is_writable($filefull)) {
        fwrite($filehandle, $text . PHP_EOL);
        fclose($filehandle);

        $returnvalue = true;
      }
    }

    return $returnvalue;
  }


  function get_files($path, $extensies, $recursive) {
    //to do: checken op extensies
    //to do: recursive

    $files = array();

    $count = 0;
    if ( $dir = @opendir($path) ) {
      while ( false !== ($file = readdir($dir)) ) {
        if ($file != "." && $file != ".." && !isempty($file)) {
          if (!is_dir($file)) {
//          if ( preg_match("/(\.gif|\.jpg)$/", $file) ) {
            $files[$count] = $file;
            $count++;
//          }
          }
        }
      }
      closedir($dir);
    }
    return $files;
  }


  function glob_recursive($pattern, $flags = 0) {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
  }


  function dir_tree($dir, $recursive) {
    $paths = '';
    $stack[] = $dir;
    while ($stack) {
      $thisdir = array_pop($stack);
      if ($dircont = scandir($thisdir)) {
        $i=0;
        while (isset($dircont[$i])) {
          if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
            $current_file = "{$thisdir}/{$dircont[$i]}";
            if (is_file($current_file)) {
              $paths[] = "{$thisdir}/{$dircont[$i]}";
            } elseif (is_dir($current_file)) {
              if ($recursive) {
                $paths[] = "{$thisdir}/{$dircont[$i]}";
                $stack[] = $current_file;
              }
            }
          }
          $i++;
        }
      }
    }
    return $paths;
  }


  function get_sorted_files($dir, $exp, $how='name', $desc=0) {
     if (isempty($exp)) {
       $exp = '(.+)';
     }
     $r = array();
     $dh = @opendir($dir);
     if ($dh) {
         while (($fname = readdir($dh)) !== false) {
             if (preg_match($exp, $fname)) {
                 $stat = stat("$dir/$fname");
                 $r[$fname] = ($how == 'name')? $fname: $stat[$how];
             }
         }
         closedir($dh);
         if ($desc) {
             arsort($r);
         }
         else {
             asort($r);
         }
     }
     return(array_keys($r));
  }


  function unique_filename($path, $filename, $originalfilename, $counter) {
    if (isempty($originalfilename)) {
      $originalfilename = $filename;
    }
    if (file_exists($path.'/'.$filename)) {
      $newcounter = $counter + 1;
      $newfilename = filename_noextension($originalfilename).'_'.$newcounter.'.'.filename_extension($originalfilename);
      return unique_filename($path, $newfilename, $originalfilename, $newcounter);
    } else {
      return $filename;
    }
  }


/*
  function LoadFiles($Pattern) {
    $Files = array();

    $It = dir('[pattern]');

    if (! $It)
      die('Cannot list files for ' . $Pattern);

    while ($Filename = $it->read()) {
      if ($Filename == '.' || $Filename == '..') {
        continue;
      }

      $LastModified = filemtime($Filename);
      $Files[] = array($Filename, $LastModified);
    }

    dir->close();

    return $Files;
  }

  function DateCmp($a, $b) {
    return ($a[1] < $b[1]) ? -1 : 0;
  }

  function SortByDate(&$Files) {
    usort($Files, 'DateCmp');
  }

*/


  function slashdir($path) {
    if (right($path,1)=="\\") {
      return $path;
    } else {
      return $path."\\";
    }
  }


  function windowsdir($path) {
    $windowspath = str_ireplace("/", '\\', $path);
    return $windowspath;
  }


  function filename_path($filename) {
    $path_parts = pathinfo($filename);

    return array_key_exists("dirname", $path_parts) ? $path_parts["dirname"] : "";
  }


  function filename_nopath($filename) {
    $path_parts = pathinfo($filename);

    return array_key_exists("basename", $path_parts) ? $path_parts["basename"] : "";
  }


  function filename_extension($filename) {
    $path_parts = pathinfo($filename);

    return array_key_exists("extension", $path_parts) ? $path_parts["extension"] : "";
  }


  function filename_noextension($filename) {
    if (contains($filename, ".")) {
      $path_parts = pathinfo($filename);

      return (array_key_exists("basename", $path_parts) && array_key_exists("extension", $path_parts)) ? substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1))) : "";
    } else {
      return $filename;
    }
  }


  function ParseURLplus($url){
    $URLpcs = (parse_url($url));
    $PathPcs = explode("/",$URLpcs['path']);
    $URLpcs['file'] = end($PathPcs);
    unset($PathPcs[key($PathPcs)]);
    $URLpcs['dir'] = implode("/",$PathPcs);

    return ($URLpcs);
  }

  function cleanurl($url) {
    //haalt alles achter ? en # weg
    return leftpart(leftpart($url, "?"), "#");
  }

  function url_path($url) {
//    $url_parts = parse_url($url);
//    return $url_parts("

    $url_parts = ParseURLplus($url);

    return $url_parts['dir'];

  }


  function server_path($clientpath, $rootpath="") {
    $strippedclientpath = leftpart(leftpart($clientpath, '?'), '#');

    if (!$rootpath) {
      $rootpath = __DIR__ . "/../../";
    }

    $serverpath =  striplastslash($rootpath) . "/" . stripfirstslash($strippedclientpath);

    return $serverpath;
  };


  function paramfile_exists($filename) {
    $parts = parse_url($filename);

    return file_exists($parts['path']);
  };


  function url_question_ampersand($url) {
    if (contains($url, "?")) {
      return "&";
    } else {
      return "?";
    }
  };


  function url_add_timestamp($url, $addtimestamp=true) {
    $serverfile = server_path($url);

    if (file_exists($serverfile)) {
      $timestamp = filemtime($serverfile);

      return $url . ($addtimestamp ? url_question_ampersand($url). "timestamp=".$timestamp : "");
    } else {
      return $url . ($addtimestamp ? url_question_ampersand($url) . "notfound=".rand(0,10000) : "");
    }
  };


  function file_add_timestamp($filename, $addtimestamp=true) {
    if (file_exists($filename)) {
      $timestamp = filemtime($filename);

      return $filename . ($addtimestamp ? url_question_ampersand($filename). "timestamp=".$timestamp : "");
    } else {
      return $filename . ($addtimestamp ? url_question_ampersand($filename) . "notfound=".rand(0,10000) : "");
    }
  };


  function add_timestamp($fileorurl, $addtimestamp=true) {
    if (file_exists($fileorurl)) {
      return file_add_timestamp($fileorurl, $addtimestamp);
    } else {
      return url_add_timestamp($fileorurl, $addtimestamp);
    }
  };


  /*
  function url_exists($url) {
    $url = str_replace("http://", "", $url);
    if (strstr($url, "/")) {
      $url = explode("/", $url, 2);
      $url[1] = "/".$url[1];
    } else {
      $url = array($url, "/");
    }

    $fh = fsockopen($url[0], 80);
    if ($fh) {
      fputs($fh,"GET ".$url[1]." HTTP/1.1\nHost:".$url[0]."\n\n");
      if (fread($fh, 22) == "HTTP/1.1 404 Not Found") {
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      return FALSE;
    }
  }
*/


  function url_exists($url) {
    if(!strstr($url, "http://"))
    {
        $url = "http://".$url;
    }

    $fp = @fsockopen($url, 80);

    if($fp === false)
    {
        return false;
    }
    return true;
  }


  /*
  function upload_file($field = '', $dirPath = '', $maxSize = 100000, $allowed = array()) {
    foreach ($_FILES[$field] as $key => $val)
        $$key = $val;

    if ((!is_uploaded_file($tmp_name)) || ($error != 0) || ($size == 0) || ($size > $maxSize))
        return false;    // file failed basic validation checks

    if ((is_array($allowed)) && (!empty($allowed)))
        if (!in_array($type, $allowed))
            return false;    // file is not an allowed type

    if (!is_dir($dirPath)) {
      mkdir($dirPath);
    }

    if (move_uploaded_file($tmp_name, $dirPath))
        return $path;

    return false;
  };
  */


  function display_filesize($filesize){
    if(is_numeric($filesize)){
      $decr = 1024; $step = 0;
      $prefix = array('Byte','KB','MB','GB','TB','PB');

      while(($filesize / $decr) > 0.9){
        $filesize = $filesize / $decr;
        $step++;
      }

      return round($filesize,2).' '.$prefix[$step];
    } else {
      return 'NaN';
    }
  };


  function download_file( $fullPath ){
    // Must be fresh start
    if( headers_sent() )
      die('Headers Sent');

    // Required for some browsers
    if(ini_get('zlib.output_compression'))
      ini_set('zlib.output_compression', 'Off');

    // File Exists?
    if( file_exists($fullPath) ){
      // Parse Info / Get Extension
      $fsize = filesize($fullPath);
      $path_parts = pathinfo($fullPath);
      $ext = strtolower($path_parts["extension"]);

      // Determine Content Type
      switch ($ext) {
        case "pdf"  : $ctype="application/pdf"; break;
        case "exe"  : $ctype="application/octet-stream"; break;
        case "zip"  : $ctype="application/zip"; break;
        case "doc"  : $ctype="application/msword"; break;
        case "xlsx" : $ctype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";break;
        case "xls"  : $ctype="application/vnd.ms-excel"; break;
        case "ppt"  : $ctype="application/vnd.ms-powerpoint"; break;
        case "gif"  : $ctype="image/gif"; break;
        case "png"  : $ctype="image/png"; break;
        case "jpeg" :
        case "jpg"  : $ctype="image/jpg"; break;
        default: $ctype="application/force-download";
      }

      header("Pragma: public"); // required
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false); // required for certain browsers
      header("Content-Type: $ctype");
      header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: ".$fsize);
      ob_clean();
      flush();
      readfile( $fullPath );

    } else {
      die('File Not Found');
    }
  };


  function file_get_contents_exists($fn) {
    if (file_exists($fn)
    &&  is_file($fn)) {
      return file_get_contents($fn);
    } else {
      return "";
    }
  };


  function file_include_exists($fn) {
    if (file_exists($fn)) {
      include_once $fn;
    }

    return "";
  };


  function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);

     return mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
  };



  function recurse_copy($src,$dst, $mode=0755) {
    if (!directory_exists($dst)) {
      @mkpath($dst, $mode);
    }

    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
          if ( is_dir($src . '/' . $file) ) {
              recurse_copy($src . '/' . $file,$dst . '/' . $file);
          }
          else {
              copy($src . '/' . $file,$dst . '/' . $file);
          }
      }
    }
    closedir($dir);
  };


  function emptydir($path, $pattern="*", $recursive=false, $removepath = false) {
    ////TODO: recursive
    //foreach (glob("{$path}/{$pattern}") as $filename) {
    //  unlink($filename);
    //}

    //array_map('unlink', glob("{$path}/{$pattern}"));
    if ($path) {
      if(is_file($path)){
        return @unlink($path);
      } elseif(is_dir($path)) {
        $scan = glob(rtrim($path,'/')."/{$pattern}");

        if ($recursive) {
          foreach($scan as $index=>$dirpath){
            emptydir($dirpath, $pattern, $recursive, true);
          }
        }

        if ($removepath) {
          return @rmdir($path);
        }
      }
    } else {
      return false;
    }
  }


  function emptypath($dirname,$self_delete=false) {
    if (is_dir($dirname)) {
      $dir_handle = opendir($dirname);

      if (!$dir_handle) {
        return false;
      } else {
        while($file = readdir($dir_handle)) {
          if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file)) {
              @unlink($dirname."/".$file);
            } else {
              emptypath($dirname.'/'.$file,true);
            }
          }
        }
        closedir($dir_handle);

        if ($self_delete){
          @rmdir($dirname);
        }
        return true;
      }
    }
  }


  function filename_concat($filepath, $filename) {
    return striplastslash($filepath) . "/" . stripfirstslash($filename);
  }

  function is_dir_empty($dir) {
    if (!is_readable($dir)) return NULL;
    return (count(scandir($dir)) == 2);
  }

  function is_valid_filename($filename) {
    return preg_match('/^[^\/\?\*:;{}\\\]+\.[^\/\?\*:;{}\\\]+$/', $filename);
  }

  function is_valid_foldername($foldername) {
    return strpbrk($foldername, "\\/?%*:|\"<>") === FALSE;
  }

  function is_valid_fileorfoldername($fileorfoldername) {
    return strpbrk($fileorfoldername, "\\/?%*:|\"<>") === FALSE;
  }

  function is_valid_filepath($path) {
    $path = trim($path);
    if(preg_match('/^[^*?"<>|:]*$/',$path)) return true; // good to go

    if(!defined('WINDOWS_SERVER'))
    {
        $tmp = dirname(__FILE__);
        if (strpos($tmp, '/', 0)!==false) define('WINDOWS_SERVER', false);
        else define('WINDOWS_SERVER', true);
    }

    /*first, we need to check if the system is windows*/
    if(WINDOWS_SERVER)
    {
        if(strpos($path, ":") == 1 && preg_match('/[a-zA-Z]/', $path[0])) // check if it's something like C:\
        {
            $tmp = substr($path,2);
            $bool = preg_match('/^[^*?"<>|:]*$/',$tmp);
            return ($bool == 1); // so that it will return only true and false
        }
        return false;
    }
    //else // else is not needed
         return false; // that t
  };

  function directory_exists($path) {
    return file_exists($path);
  }


  function file_put_base64($output_file, $base64_string) {
    $ifp = fopen($output_file, "wb");

    if (contains($base64_string, ",")) {
      $data = explode(',', $base64_string);
      $base64_string = $data[1];
    }

    fwrite($ifp, base64_decode($base64_string));
    fclose($ifp);

    return true;
  }


  function is_absolute_path($path) {
    //TODO: paths die met een / beginnen worden NIET als absoluut gezien door deze functie
    if (startswith(trim($path), ["http:", "https:", "ftp:", "sftp:", "svn:"])) {
      return true;
    }

    if($path === null || $path === '') throw new Exception("Empty path");
    return $path[0] === DIRECTORY_SEPARATOR || preg_match('~\A[A-Z]:(?![^/\\\\])~i',$path) > 0;
  }

  /*
  function is_absolute_path( $path ) {
    if ( realpath($path) == $path )
      return true;

    if ( strlen($path) == 0 || $path[0] == '.' )
      return false;
  }
  */

  /*
  function is_absolute_path($path) {
    $returnvalue = false;

    preg_match_all('#^[a-zA-Z]*:|[\s\v][a-zA-Z]*#i', $path, $matches);

    if (!is_empty($matches)) {
      if (!is_empty($matches[0])) {
        returnvalue=true;
      }
    }

    return $returnvalue;
  }
  */

    /**
     * Normalize path.
     * @param string $path
     * @throws LogicException
     * @return string
     *//*
  function normalizePath($path) {
    // Remove any kind of funky unicode whitespace
    $normalized = preg_replace('#\p{C}+|^\./#u', '', $path);
    $normalized = normalizeRelativePath($normalized);
//    if (preg_match('#/\.{2}|^\.{2}/|^\.{2}$#', $normalized)) {
//      throw new LogicException(
//          'Path is outside of the defined root, path: [' . $path . '], resolved: [' . $normalized . ']'
//          );
 //   }
    $normalized = preg_replace('#\\\{2,}#', '\\', trim($normalized, '\\'));
    $normalized = preg_replace('#/{2,}#', '/', trim($normalized, '/'));
    return $normalized;
  }
  */
  /**
   * Normalize relative directories in a path.
   * @param string $path
   * @return string
   */
  function normalizeRelativePath($path) {
    // Path remove self referring paths ("/./").
    $path = preg_replace('#/\.(?=/)|^\./|/\./?$#', '', $path);
    // Regex for resolving relative paths
    $regex = '#/*[^/\.]+/\.\.#Uu';
    while (preg_match($regex, $path)) {
      $path = preg_replace($regex, '', $path);
    }
    return $path;
  }


   /**
   * Normalize path
   *
   * @param   string  $path
   * @param   string  $separator
   * @return  string  normalized path
   */
  function normalizePath($path, $separator = '\\/')    {
    // Remove any kind of funky unicode whitespace
    $normalized = preg_replace('#\p{C}+|^\./#u', '', $path);

    // Path remove self referring paths ("/./").
    $normalized = preg_replace('#/\.(?=/)|^\./|\./$#', '', $normalized);

    // Regex for resolving relative paths
    $regex = '#\/*[^/\.]+/\.\.#Uu';

    while (preg_match($regex, $normalized)) {
      $normalized = preg_replace($regex, '', $normalized);
    }

    if (preg_match('#/\.{2}|\.{2}/#', $normalized)) {
      //throw new LogicException('Path is outside of the defined root, path: [' . $path . '], resolved: [' . $normalized . ']');
    }

    return trim($normalized, $separator);
  }



  function removeDots($path) {
    $root = ($path[0] === '/') ? '/' : '';

    $segments = explode('/', trim($path, '/'));
    $ret = array();
    foreach($segments as $segment){
      if (($segment == '.') || empty($segment)) {
        continue;
      }
      if ($segment == '..') {
        array_pop($ret);
      } else {
        array_push($ret, $segment);
      }
    }
    return $root . implode('/', $ret);
  }
?>