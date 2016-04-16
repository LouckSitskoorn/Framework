<?php
  //NAMESPACE
  namespace SB\Models;

  //USES
  use View;
  use SB\Functions as sb;
  use SB\Classes\Database\SB_DB;

  //INCLUDES
  include __DIR__ . "/../Functions/blade_functions.php";

  //CLASSES
  class Model
  extends \Illuminate\Database\Eloquent\Model {
    //public properties
    public static $fields="";
    public static $id;
    public static $paramarray=[];
    public static $sqlfilename="";

    public static $pagesize;

    //implementation
    public static function initProperties($id, $paramarray=[]) {
      static::$id = $id;

      static::$paramarray = array_merge(
          $paramarray
        , [
          ]
      );
    }


    /**
     * get query result
     *
     * @return array
    */
    public static function getResult() {
      $fields           = [];
      $records          = [];
      $sqlfilecontents  = file_get_contents(static::$sqlfilename);
      $sqlresult        = [];

      //get sql statement as blade view
      $sqlviewcontents = View::make(
          ['template' => $sqlfilecontents]
          , static::$paramarray
          );
      //$sqlviewcontents = sb\bladeCompile($sqlfilecontents);

      //sql contains sections?
      $sections = $sqlviewcontents->renderSections();

      if (count($sections) > 0) {
        //execute each section
        foreach ($sections as $sectionkey=>$section) {
          if (!sb\is_empty($section)) {
            //sb\log($section);

            $sqlresult  = SB_DB::select(SB_DB::raw($section), []);
          }
        }
      } else {
        //execute sql
        $sqlresult = SB_DB::select(SB_DB::raw($sqlviewcontents->render()), []);
      }

      //limit fields?
      if ($sqlresult
      &&  static::$fields) {
        $fields = explode(",", static::$fields);

        foreach ($sqlresult as $key=>$record) {
          foreach($fields as $fielditem) {
            $fielditem = trim($fielditem);
            $records[$key][$fielditem]  = $record->$fielditem;
          }
        }
      } else {
        $records          = $sqlresult;
      }

      //encrypt ID fields?
      //$records = sb\encryptLaravelResult($records);

      /*
      return [
          self::table => $records
      ];
      */
      return $records;
    }


    /**
     * get record from query result
     *
     * @return array
    */
    public static function getRecord($index=0) {
      $result = static::getResult();

      if (is_array($result)
      &&  isset($result[$index]) ) {
        return $result[$index];
      } else {
        return [];
      }
    }

  }

