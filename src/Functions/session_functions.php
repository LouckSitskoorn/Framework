<?
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDES
  include_once __DIR__ . "/debug_functions.php";
  include_once __DIR__ . "/file_functions.php";
  include_once __DIR__ . "/string_functions.php";

  //USES
  use Session;
  use SB\Functions as sb;

  //FUNCTIONS
  function write_session($filename, $text, $key="") {
    $sessionid  = Session::getId();
    $filename   = sb\filename_noextension($filename) . ".txt";

    if ($sessionid) {
      $filefull   = __DIR__ . "/../../../storage/framework/sessions/" . $sessionid . "_map/" . $filename;

      writefile($filefull, $text);
    }
  }

  function read_session($filename, $key="") {
    $sessionid  = Session::getId();
    $filename   = sb\filename_noextension($filename) . ".txt";

    if ($sessionid) {
      $filefull   = __DIR__ . "/../../../storage/framework/sessions/" . $sessionid . "_map/" . $filename;

      return file_get_contents($filefull);
    }

    return false;
  }