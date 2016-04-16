<?
  //NAMESPACE
  namespace SB\Functions;

  //USE classes
  use SB\Classes\Basic\SB_Log;

  //INCLUDE functions
  include_once __DIR__ . "/debug_functions.php";
  include_once __DIR__ . "/string_functions.php";
  include_once __DIR__ . "/file_functions.php";

  //GLOBALS
  $logger = new SB_Log(env("LOG_HANDLER", "FIREPHP"), env("LOG_NAME", "SBLog"), 100);

  //functions
  function log($var, $level=100) {
    global $logger;

    if (!isset($logger)) {
      $logger = new SB_Log("FIREPHP", "SBLog", $level);
    }

    $obstatus = ob_get_status();
    //if (!empty($obstatus)) {
      $logger->log($var, $level);
    //}
  }


  function logtext_tofile($logpath, $logfilename, $text, $logmode="a+", $logsoortid="UNKNOWN", $logtypeid="UNKNOWN", $logoperation="UNKNOWN", $logoperationtype="UNKNOWN", $error=false, $errorcode="", $message="") {
    global $_SESSION;

    //init variables
    $log      = $error;
    $logtext  = "";

    //alleen van SUBMIT statements het SQL statement bewaren
    if ( (   comparetext($logoperationtype, "SUBMIT")
          || comparetext($logoperationtype, "INSERT")
          || comparetext($logoperationtype, "UPDATE")
          || comparetext($logoperationtype, "UNKNOWN") )
    &&  !comparetext($logtypeid, "SELECT")
    &&  !comparetext($logtypeid, "SCRIPT") ) {
      $log = true;
    }

    if ($log
    &&  !is_empty($logpath)) {
      //voeg datum toe
      $logtext .=  "#" . date('d-m-Y H:i:s', time()) . PHP_EOL;

      //voeg errorcode toe
      if ($error) {
        $logtext .= "ERROR : " . $errorcode . PHP_EOL;
      }

      //voeg message toe
      if ($error) {
        $logtext .= "MESSAGE : " . $message . PHP_EOL;
      }

      //voeg text toe
      if ($text) {
        $logtext .= PHP_EOL . $text . PHP_EOL;
      }

      //make path
      if (!directory_exists($logpath)) {
        mkpath($logpath);
      }

      //create file
      $filefull   = striplastslash($logpath) . "/" . stripfirstslash($logfilename);
      $filehandle = fopen($filefull, $logmode);

      if (is_writable($filefull)) {
        //chmod($filefull, 0755);
        fwrite($filehandle, $logtext);
        fclose($filehandle);

        return true;
      } else {
        return false;
      }

    } else {
      return false;
    }
  }


  function logtext_totable_insert($db, $sql, $xml, $logtablename="logs", $logsoortid="SQL", $logtypeid="UNKNOWN", $logoperation="UNKNOWN", $logoperationtype="UNKNOWN", $tablename="", $primaryfieldname="", $primaryfieldvalue="", $error=false, $errorcode="", $message="", $duration="NULL", $maxduration=0) {
    global $_SESSION;

    $logid  = null;

    if ($db) {
      $datum       = addslashes(date("Y/m/d G:i:s", time()));

      if (isempty($logtablename)) {
        $logtablename  = "logs";
      }

      $ip            = is_array($_SESSION) && isset($_SESSION["session"]) ? isset($_SESSION["session"]["ip"]) ? $_SESSION["session"]["ip"] : $_SERVER["REMOTE_ADDR"] : $_SERVER["REMOTE_ADDR"];
      $organisatieid = is_array($_SESSION) && isset($_SESSION["account"]) ? isset($_SESSION["account"]["organisatieid"]) ? $_SESSION["account"]["organisatieid"] : NULL : NULL;
      $gebruikerid   = is_array($_SESSION) && isset($_SESSION["account"]) ? isset($_SESSION["account"]["gebruikerid"]) ? $_SESSION["account"]["gebruikerid"] : NULL : NULL;

      $logsql  = "";
      $logsql .= "INSERT INTO " . $logtablename;
      $logsql .= "  (OrganisatieID, GebruikerID, LogsoortID, LogtypeID, Operation, OperationType, Datum, IP, Tablename, PrimaryFieldName, PrimaryFieldValue, SQLStatement, XMLSubmit, Error, ErrorCode, Message, Duration) ";
      $logsql .= "VALUES ";
      $logsql .= "  ('" . $organisatieid . "', '" . $gebruikerid . "', '{$logsoortid}', '{$logtypeid}', '{$logoperation}', '{$logoperationtype}', '$datum', '" . $ip . "', ". (is_null($tablename) ? "NULL" : "'$tablename'") . ", ". (is_null($primaryfieldname) ? "NULL" : "'$primaryfieldname'") . ", ". (is_null($primaryfieldvalue) ? "NULL" : "'$primaryfieldvalue'") . ", '" . trim(addslashes($sql)) . "', '" . trim(str_ireplace("'", "\'", $xml)) . "', '" . booltostr2($error) . "', '" . coalesce($errorcode, "") . "', '" . addslashes(coalesce($message, "")) . "', $duration)";

      try {
        switch (get_class($db)) {
          case "mysqli" :
            $logresult  = $db->query($logsql);
            $logid      = $db->insert_id;
            break;

          case "PDO"    :
            $logresult  = $db->query($logsql);
            $logid      = $db->lastInsertId();;
            break;
        }
      } catch (Exception $e) {
          switch (get_class($db)) {
            case "mysqli" :
              fbb($db->errno);
              fbb($db->error);
              break;

            case "PDO"    :
              fbb($db->errorCode());
              fbb($db->errorInfo());
              break;
          }
      }
    }

    return $logid;
  }


  function logtext_totable_update($db, $logid, $logtablename="logs", $logsoortid="UNKNOWN", $logtypeid="UNKNOWN", $logoperation="UNKNOWN", $logoperationtype="UNKNOWN", $tablename="", $primaryfieldname="", $primaryfieldvalue="", $error=false, $errorcode="", $message="", $duration=0, $maxduration=0) {
    global $_SESSION;

    if ($db
    &&  $logid) {
      $datum        = date("Y/m/d G:i", time());
      $komma        = "";

      //log tablename
      if (isempty($logtablename)) {
        $logtablename  = "logs";
      }

      //UPDATE
      $logsql  = "";
      $logsql .= "UPDATE " . $logtablename;
      $logsql .= "   SET ";

      if ($logsoortid != "" && $logsoortid != "UNKNOWN")              {$logsql .= $komma . "LogsoortID = '" . $logsoortid . "' ";               $komma  = ", ";}
      if ($logtypeid != "" && $logtypeid != "UNKNOWN")                {$logsql .= $komma . "LogtypeID = '" . $logtypeid . "' ";                 $komma  = ", ";}
      if ($logoperation != "" && $logoperation != "UNKNOWN")          {$logsql .= $komma . "Operation = '" . $logoperation . "' ";              $komma  = ", ";}
      if ($logoperationtype != "" && $logoperationtype != "UNKNOWN")  {$logsql .= $komma . "OperationType = '" . $logoperationtype . "' ";      $komma  = ", ";}
      if ($tablename != "" && $tablename != "UNKNOWN")                {$logsql .= $komma . "TableName = '" . $tablename . "' ";                 $komma  = ", ";}
      if ($primaryfieldname != "" && $primaryfieldname != null)       {$logsql .= $komma . "PrimaryFieldName  = '" . $primaryfieldname . "' ";  $komma  = ", ";}
      if ($primaryfieldvalue != "" && $primaryfieldvalue != null)     {$logsql .= $komma . "PrimaryFieldValue = '" . $primaryfieldvalue . "' "; $komma  = ", ";}
      if ($error != "" && $error != null)                             {$logsql .= $komma . "Error = '" . booltostr2($error) . "' ";             $komma  = ", ";}
      if ($errorcode != "" && $errorcode != null)                     {$logsql .= $komma . "ErrorCode = '" . $errorcode . "' ";                 $komma  = ", ";}
      if ($message != "" && $message != null)                         {$logsql .= $komma . "Message = '" . addslashes($message) . "' ";         $komma  = ", ";}
      if ($duration != "" && $duration != 0)                          {$logsql .= $komma . "Duration = " . $duration . " ";                     $komma  = ", ";}

      if ($logsoortid == "SQL"
      &&  ($logtypeid == "SELECT" || $logtypeid == "SCRIPT")
      &&  $duration <= $maxduration
      &&  !$error) {
        //leegmaken van sql/xml velden bij kortdurende SELECT statements
        $logsql .= $komma . "SQLStatement = \"\"";  $komma =", ";
        $logsql .= $komma . "XMLSubmit = \"\"";     $komma =", ";
        $logsql .= $komma . "XMLSelection = \"\"";  $komma =", ";
        $logsql .= $komma . "XMLSearch = \"\"";     $komma =", ";
      }

      //WHERE
      $logsql .= " WHERE LogID = '" . $logid . "'";

      //execute log query
      try {
        switch (get_class($db)) {
          case "mysqli" :
            $logresult  = $db->query($logsql);
            $logid      = $db->insert_id;
            break;

          case "PDO"    :
            $logresult  = $db->query($logsql);
            $logid      = $db->lastInsertId();
            break;
        }
      } catch (Exception $e) {
          switch (get_class($db)) {
            case "mysqli" :
              fbb($db->errno);
              fbb($db->error);
              break;

            case "PDO"    :
              fbb($db->errorCode());
              fbb($db->errorInfo());
              break;
          }
      }
    }

    return $logid;
  }
?>