<?php
  //NAMESPACE
  namespace SB\Services;

  //USES
  use SB\Classes\Basic\SB_Object;
  use SB\Classes\Database\SB_DB;
  use SB\Interfaces\ManipulatorInterface;
  use SB\Functions as sb;

  //CLASSES
  class ManipulatorService
  extends SB_Object
  implements ManipulatorInterface {
    //published properties
    public $Describe            = false;
    public $Distinct            = true;
    public $EnabledFieldname    = "Enabled";
    public $EnabledTrueValue    = "TRUE";
    public $EnabledFalseValue   = "FALSE";
    public $EnabledDefaultValue = "TRUE";
    public $Fields              = null;       //string of comma-separated fieldnames or array of field objects
    public $IDFieldname         = "";         //eg LokatieID
    public $IDsFieldname        = "";         //eg LokatieIDs
    public $IncludeDisabled     = false;
    public $Lookups             = [];         //array of lookup field objects
    public $Limit               = false;
    public $OrderFieldname      = "";         //eg Naam
    public $SortOrder           = "ASC";
    public $RemoveIDsFieldname  = true;
    public $Tablename           = "";         //eg lokaties
    public $TableAbbrevation    = "";         //eg l
    public $TypeAliases         = [];         //eg ["organisatie"=>"OrganisatieID"]
    public $TypesIncluded       = [];         //array of manipulatortypes
    public $TypesExcluded       = [];         //array of manipulatortypes

    public static $StaticTest="Testje";

    //implementation
    public function __construct($propertyarray=[]) {
      //inherited
      parent::__construct($propertyarray);

      $this->init();
    }

    /**
     * Init manipulator object
     *
     * @param  boolean $initObject
     */
    public function init($initObject=null) {
      //inherited
      parent::init($initObject);

      //explode fields?
      if (is_string($this->Fields)) {
        $this->Fields  = explode(",", $this->Fields);
      }

      //explode types?
      if (is_string($this->TypesIncluded)) {
        $this->TypesIncluded  = explode(",", $this->TypesIncluded);
      }
      if (is_string($this->TypesExcluded)) {
        $this->TypesExcluded  = explode(",", $this->TypesExcluded);
      }

      //tableabbrevation
      if (sb\is_empty($this->TableAbbrevation)
      && !sb\is_empty($this->Tablename)) {
        $this->TableAbbrevation = $this->Tablename[0];
      }

      //idsfieldname
      if (sb\is_empty($this->IDsFieldname)
      && !sb\is_empty($this->IDFieldname)) {
        $this->IDsFieldname = $this->IDFieldname . "s";
      }
    }


    /**
     * Manipulate a (sql) result
     *
     * @param  array $result
     * @param  array $propertyarray
     * @return array
     */
    public function manipulate($result, $propertyarray=[]) {
      //init variables
      $count            = 0;
      $csv              = "";
      $csvkey           = "";
      $csvkeyfunction   = "";
      $csvkeypart       = "";
      $csvkeyoperator   = "";
      $csvparts         = [];
      $csvpartresults   = [];
      $csvreadonly      = false;
      $csvreceiveonly   = false;
      $csvsendonly      = false;
      $csvrestant       = "";

      $fieldname        = "";
      $idsfieldnames    = [];
      $idresult         = [];
      $returnresult     = [];
      $sql              = "";
      $sqlwhere         = "";
      $tableabbrevation = "";

      //set properties
      $this->setProperties($propertyarray);
      $this->init();

      //explode types?
      if (is_string($this->TypesIncluded)) {
        $this->TypesIncluded  = explode(",", $this->TypesIncluded);
      }
      if (is_string($this->TypesExcluded)) {
        $this->TypesExcluded  = explode(",", $this->TypesExcluded);
      }

      //result is object?
      if (is_object($result)) {
        $result = array($result);
      }

      //result?
      if (is_array($result)
      &&  !empty($result)) {
        //include cached arrays?
        //if ($includearrays) {
        //  include_once __DIR__ . "/../source/manipulator_array.php";
        //}

        //fieldnames array samenstellen
        if (!$this->IDsFieldname) {
          foreach ($result[0] as $fieldkey=>$fieldvalue) {
            $idsfieldnames[] = trim($fieldkey);
          }
        } else {
          $idsfieldnames   = sb\multi_explode(",;", $this->IDsFieldname);
        }

        //records aflopen
        foreach ($result as $key => $record) {
          //limit?
          if ($this->Limit
          &&  $count >= $this->Limit) {
            break;
          }

          //record is object?
          if (is_object($record)) {
            $record = (array) $record;
          }

          //fields aflopen
          if (is_array($record)
          && !empty($record)) {

            foreach ($idsfieldnames as $idsfieldkey=>$idsfieldname) {
              //get csv
              $csv = is_string($idsfieldname) ? (array_key_exists($idsfieldname, $record) ? $record[$idsfieldname] : false) : false;

              if (!sb\is_empty($csv)) {
                //csv splitsen in delen
                if ((stripos($csv, "[") !== false &&  stripos($csv, "]") !== false)
                ||  (stripos($csv, "{") !== false &&  stripos($csv, "}") !== false)) {
                  //preg_match_all("/\[[^\]]+\]/", $csv, $csvbracketparts);         //[...] delen vinden
                  preg_match_all("/[\[|\{][^\]]+[\]|\}]/iU", $csv, $csvbracketparts); //[...] of {...} delen vinden

                  if (count($csvbracketparts) >= 1) {
                    $csvrestant = str_ireplace($csvbracketparts[0], "", $csv);  //[...] delen verwijderen uit string
                    $csvparts   = sb\array_remove_empty(array_merge($csvbracketparts[0], explode(";", $csvrestant)));
                  }
                } else {
                  $csvparts = sb\array_remove_empty(explode(";", $csv));
                  //$csvparts = (array) $csv;
                }

                //csv delen aflopen
                foreach($csvparts as $csvpart) {
                  //init variables
                  $sql      = "";
                  $sqlwhere = "";
                  $idresult = [];

                  if (stripos($csvpart, "[") === FALSE
                  &&  stripos($csvpart, "{") === FALSE) {
                    //ZONDER HAKEN maar SPECIFIEKE ID(s)
                    $csvkeypart     = sb\removehooks(sb\leftpart($csvpart, ";"));
                    $csvkeyfunction = "";
                    $csvreadonly    = preg_match("/readonly=true/i", $csvpart);

                    //if ($lokaties
                    //&&  !empty($lokaties)) {
                    //  $returnindex = sb\array_search_assoc($returnresult, "LokatieID", $lokaties[$csvkeypart]["LokatieID"]);
                    //
                    //  if ($returnindex === false) {
                    //    $returnresult[]  = array_merge($lokaties[$csvkeypart], $record);
                    //  } else {
                    //    $returnresult[$returnindex]  = array_merge($lokaties[$csvkeypart], $record, $returnresult[$returnindex]);
                    //  }
                    //} else {
                      //WHERE
                      $sqlwhere .= "
                          WHERE (" . $this->TableAbbrevation . "." .$this->IDFieldname . " IN (" . $csvkeypart . ") )
                      ";

                      //AND
                      if (!$this->IncludeDisabled) {
                        $sqlwhere .= "
                            AND (COALESCE(" . $this->TableAbbrevation . "." .$this->EnabledFieldname . ", \"" . $this->EnabledDefaultValue . "\") != \"" . $this->EnabledFalseValue . "\" )
                        ";
                      }

                    //}

                  } else if (stripos($csvpart, "[") === FALSE
                         &&  stripos($csvpart, "{") !== FALSE) {
                    //MET ACCOLADES     eg {OrganisatieID in (4,173)}
                    $csvkeypart     = sb\leftpart($csvpart, ";");
                    $csvkeyfunction = "";

                    $csvreadonly    = preg_match("/readonly=true|readonly/i", $csvpart);
                    $csvsendonly    = preg_match("/sendonly=true|sendonly/i", $csvpart);
                    $csvreceiveonly = preg_match("/receiveonly=true|receiveonly/i", $csvpart);

                    $sqlwhere .= "
                          WHERE (" . sb\removebraces($csvkeypart) . ")
                    ";

                  } else if (stripos($csvpart, "[") !== FALSE
                         &&  stripos($csvpart, "{") === FALSE) {

                    //MET RECHTE HAKEN  eg [organisatie]  [organisatie=4]  [OrganisatieID=4]
                    $csvkeypart     = sb\leftpart($csvpart, ";");
                    $csvkeyfunction = "";

                    $csvreadonly    = preg_match("/readonly=true|readonly/i", $csvpart);
                    $csvsendonly    = preg_match("/sendonly=true|sendonly/i", $csvpart);
                    $csvreceiveonly = preg_match("/receiveonly=true|receiveonly/i", $csvpart);

                    preg_match("/>=|<=|=|in/i", $csvkeypart, $csvkeyoperators);
                    $csvkeyoperator = is_array($csvkeyoperators) && !empty($csvkeyoperators) ? $csvkeyoperators[0] : "";

                    $csvkey         = sb\removehooks(sb\leftpart($csvkeypart, $csvkeyoperator));
                    $csvvalues      = sb\removebrackets(sb\rightpart($csvkeypart, $csvkeyoperator));
                    $csvvaluearray  = explode(",", $csvvalues);

                    //alias?
                    if (array_key_exists($csvkey, $this->TypeAliases)) {
                      if (property_exists($this, $this->TypeAliases[$csvkey])) {
                        $csvkey = $this->TypeAliases[$csvkey];
                      } else if (method_exists($this, $this->TypeAliases[$csvkey])) {
                        $csvkeyfunction = $this->TypeAliases[$csvkey];
                      } else if (method_exists($this, "get" . $this->TypeAliases[$csvkey])) {
                        $csvkeyfunction = "get" . $this->TypeAliases[$csvkey];
                      } else {
                        $csvkeyfunction = "";
                      }
                    }

                    //operator?
                    if ($csvkeyoperator) {
                      if ($csvkeyoperator == "="
                      &&  count($csvvaluearray) > 1) {
                        $csvkeyoperator = "in";
                        $csvvalues      = "(" . sb\removehooks($csvvalues) . ")";
                      }

                    } else {
                      if (property_exists($this, $csvkey)) {
                        $csvkeyoperator = "=";
                        $csvvalues      = $this->$csvkey;
                      }
                    }

                    //abbrevation?
                    if (stripos($csvkey, ".") !== false) {
                      $tableabbrevation = $this->TableAbbrevation . ".";
                    } else {
                      $tableabbrevation = substr($this->Tablename, 0, 1) . ".";
                    }

                    //sql where part
                    if ($csvkey
                    &&  $csvkeyoperator
                    &&  $csvvalues) {
                      $sqlwhere .= "
                            WHERE (" . $tableabbrevation . $csvkey . " " . $csvkeyoperator . " " . $csvvalues . ")
                      ";
                    } else {
                      $sqlwhere = "";
                    }
                  }

                  //run query or function
                  if ($csvkeyfunction) {
                    //custom function
                    $returnresult = array_merge($returnresult, $this->$csvkeyfunction($csvkey, $csvkeyoperator, $csvvalues));

                  } else if ($this->IDFieldname
                         &&  $sqlwhere) {
                    //query
                    $sql          = $this->getSQL($sqlwhere);

                    $returnresult = array_merge($returnresult, $this->getRecords($sql, $record));
                  }

                } //next csvpart
              }
            } //next fieldname
          }
        }// next record
      }

      return $returnresult;
      //$developer        = false;
      //$describe         = false;  //strtobool(getkeyvalue($manipulatorparams, "describe"), false);
      //$distinct         = true;   //strtobool(getkeyvalue($manipulatorparams, "distinct"), true);
      //$fieldnames       = [];
      //$includearrays    = false;  //strtobool(getkeyvalue($manipulatorparams, "includearrays"), false);
      //$includedisabled  = false;  //strtobool(getkeyvalue($manipulatorparams, "includedisabled"), false);
      //$keepexcluded     = false;  //strtobool(getkeyvalue($manipulatorparams, "keepexcluded"), false);
      //$lokaties         = [];
      //$lokatieids       = null;
      //$lokatieid        = "";
      //$lokatiesresult   = [];
      //$returnindex      = -1;
      //$returnresult     = array();
      //$sql              = "";
      //$tolokatieids     = null;
      //$tolokatieid      = "";

      //$types            = [];     //getkeyvalue($manipulatorparams, "types");
      //$excludedtypes    = [];     //getkeyvalue($manipulatorparams, "excludedtypes", false, ";");

    }


    /**
     * Construct sql
     *
     * @param  string $sqlwhere
     * @return string
     */
    public function getSQL($sqlwhere) {
      //init variables
      $sql              = "";
      $tableabbrevation = "";
      $orderfieldname   = $this->OrderFieldname ? $this->OrderFieldname : $this->IDFieldname;

      //SELECT
      $sql .= "
          SELECT" . PHP_EOL . "
              " . $this->TableAbbrevation . "." .$this->IDFieldname . " AS " . $this->IDFieldname . PHP_EOL . "
      ";

      //FIELDS
      if ($this->Fields) {
        foreach ($this->Fields as $fieldkey=>$field) {
          if (is_object($field)) {
            $fieldname = $field->Fieldname;
          } else if (is_string($field)) {
            $fieldname = $field;
          }

          $sql .= "
            , " . $this->TableAbbrevation . "." . $fieldname . " AS " . $fieldname . PHP_EOL . "
          ";
        }
      }

      //LOOKUPS
      if ($this->Lookups) {
        foreach ($this->Lookups as $lookupkey=>$lookupfield) {
          if (is_object($lookupfield)) {
            $fieldname        = $lookupfield->Fieldname;
            $fieldnamealias   = $lookupfield->FieldnameAlias ? $lookupfield->FieldnameAlias : $lookupfield->Fieldname;
            $tableabbrevation = $lookupfield->TableAbbrevation ? $lookupfield->TableAbbrevation : substr($lookupfield->Tablename, 0, 3);

            $sql .= "
              , " . $tableabbrevation . "." . $fieldname . " AS " . $fieldnamealias . PHP_EOL . "
            ";
          }
        }
      }

      //FROM
      $sql  .= "
              FROM " . $this->Tablename ." AS " . $this->TableAbbrevation . PHP_EOL ."
      ";

      //JOINS
      if ($this->Lookups) {
        foreach ($this->Lookups as $lookupkey=>$lookupfield) {
          if (is_object($lookupfield)) {
            $lookuptablename        = $lookupfield->LookupTablename;
            $lookuptableabbrevation = $lookupfield->LookupTableAbbrevation ? $lookupfield->LookupTableAbbrevation : substr($lookupfield->LookupTablename, 0, 3);
            $lookupfieldname        = $lookupfield->LookupFieldname;
            $lookupcondition        = $lookupfield->LookupCondition;

            if ($lookupcondition) {
              $sql .= "
              LEFT JOIN " . $lookuptablename . " AS " . $lookuptableabbrevation . " ON " . $lookupcondition . ")" . PHP_EOL . "
              ";
            } else {
              $sql .= "
              LEFT JOIN " . $lookuptablename . " AS " . $lookuptableabbrevation . " USING (" . $lookupfieldname . ")" . PHP_EOL . "
              ";
            }
          }
        }
      }

      //WHERE
      if ($sqlwhere) {
        $sql  .= $sqlwhere;
      }

      //ORDER
      $tableabbrevation = stripos($this->OrderFieldname, ".") !== false ? "" : ($this->TableAbbrevation . ".");

      $sql  .= "ORDER BY " . $tableabbrevation . $orderfieldname . " " . $this->SortOrder;

      return $sql;
    }


    /**
     * run sql query
     *
     * @param  string $sql
     * @return array
     */
    public function getRecords($sql, $originalrecord=[]) {
      //init variables
      $idresult         = [];
      $returnresult     = [];

      //query uitvoeren
      $idresult = SB_DB::select($sql);

      if (!empty($idresult)) {
        foreach ($idresult as $idkey=>$idrecord) {
          if (is_object($idrecord)) {
            $idrecord = (array) $idrecord;
          }

          //remove idsfield ?
          if ($this->RemoveIDsFieldname) {
            unset($originalrecord[$this->IDsFieldname]);
          }

          //id already exists?
          $returnindex = sb\array_search_assoc($returnresult, $this->IDFieldname, $idrecord[$this->IDFieldname]);

          if ($returnindex === false) {
            $returnresult[]  = array_merge($idrecord, $originalrecord);
          } else {
            $returnresult[$returnindex]  = array_merge($idrecord, $originalrecord, $returnresult[$returnindex]);
          }
        }
      }

      return $returnresult;
    }


    public static function test($propertyarray=[]) {
      echo self::$StaticTest;
    }
  }