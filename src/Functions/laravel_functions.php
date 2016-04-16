<?php
  //NAMESPACE
  namespace SB\Functions;

  //USES
  use InvalidArgumentException;

  //INCLUDE functions
  include_once __DIR__ . "/date_functions.php";
  include_once __DIR__ . "/debug_functions.php";
  include_once __DIR__ . "/encryption_functions.php";
  include_once __DIR__ . "/file_functions.php";
  include_once __DIR__ . "/log_functions.php";
  include_once __DIR__ . "/session_functions.php";
  include_once __DIR__ . "/string_functions.php";

  //FUNCTIONS
  /**
   * Get the path to a versioned Elixir file.
   *
   * @param  string  $file
   * @return string
   *
   * @throws \InvalidArgumentException
   */
  function elixir($file) {
    static $manifest = null;

    if (is_null($manifest)) {
      $manifest = json_decode(file_get_contents(public_path('rev-manifest.json')), true);
    }

    if (isset($manifest[$file])) {
      return '/'.$manifest[$file];
    }

    throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
  }
