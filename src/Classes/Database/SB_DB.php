<?php
  //NAMESPACE
  namespace SB\Classes\Database;

  //USES
  use DB;
  use SB\Functions as sb;

  //CLASSES
  /**
   * @see \Illuminate\Database\DatabaseManager
   * @see \Illuminate\Database\Connection
   */
  class SB_DB
  extends DB {
    public static function select($query, $bindings = [], $useReadPdo = true, $logging=false) {
      $result = parent::select($query, $bindings, $useReadPdo);

      if ($logging) {
        sb\log(self::getQueryLog());
      }

      return $result;
    }

    public static function logselect($query, $bindings = [], $useReadPdo = true) {
      $result = parent::select($query, $bindings, $useReadPdo);

      sb\log(self::getQueryLog());

      return $result;
    }
  }