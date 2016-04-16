<?php
  //NAMESPACE
  namespace SB\Functions;

  //includes
  include_once __DIR__ . "/array_functions.php";
  include_once __DIR__ . "/string_functions.php";

  //functions
  function json_rows($result, $rowcount=-1) {
    //levert array van alle rows in een result
    $returnvalue=false;

    if ($result) {
      if ($rowcount == -1) {
        $rowcount     = $result->RecordCount();
      }

      $data    = array();
	    $datarow = array();

      while($row = $result->FetchRow()) {
        for ($i=0; $i < $result->FieldCount(); $i++) {
          $field = $result->FetchField($i);
          $datarow[$field->name] = $row[$field->name];
        }

        $data[] = $datarow;
      }

      $resultset = array(
                    "rowcount"  => "{$rowcount}",
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  function json_row($result) {
    //levert array van eerste row in een adodb result
    $returnvalue=false;

    if ($result) {
      $row      = $result->FetchRow();

      $data = array();
      for ($i=0; $i < $result->FieldCount(); $i++) {
        $field = $result->FetchField($i);
        $data[$field->name] = $row[$field->name];
      }

      $resultset = array(
                    'success'   =>  true,
                    'data'      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  function json_row_empty($fields, $xmldefault) {
    //levert lege json, met defaultvalues
    $returnvalue = false;

    $defaultfieldnames = array();
    $defaultfieldvalues= array();

    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, 'record')) {
          foreach ($record->children() as $field) {
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }

    //fields aflopen
    $fieldnames = explode(",", $fields);
    if (count($fieldnames) > 0 && count($defaultfieldnames) >0) {
      $data    = array();
      $datarow = array();
      foreach ($fieldnames as $fieldname) {
        $fieldname = trim($fieldname);

        $keyindex = array_search($fieldname, $defaultfieldnames);
        if ($keyindex !== FALSE) {
          //if ($defaultfieldvalues[$key] != "-1") {
            $datarow[$fieldname] = str_ireplace('"', '', $defaultfieldvalues[$keyindex]);
          //} else {
          //  $datarow[$fieldname] = null;
          //}
        } else {
          $datarow[$fieldname] = null;
        }
      }
      $data[] = $datarow;

      $resultset = array(
                    "success"   =>  true,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );
    } else {
      $resultset = array(
                    "success"   =>  true,
                    "rowcount"  => "0",
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => array()
      );
    }
    $returnvalue = $resultset;

    return $returnvalue;
  }


  function json_rows_empty($fields, $xmldefault) {
    //levert lege json, met defaultvalues
    $returnvalue = false;

    $defaultfieldnames = array();
    $defaultfieldvalues= array();

    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, 'record')) {
          foreach ($record->children() as $field) {
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }

    //fields aflopen
    $fieldnames = explode(",", $fields);
    if (count($fieldnames) > 0 && count($defaultfieldnames) >0) {
      $data    = array();
      $datarow = array();
      foreach ($fieldnames as $fieldname) {
        $fieldname = trim($fieldname);

        $keyindex = array_search($fieldname, $defaultfieldnames);
        if ($keyindex !== FALSE) {
          $datarow[$fieldname] = str_ireplace('"', '', $defaultfieldvalues[$keyindex]);
        } else {
          $datarow[$fieldname] = null;
        }
      }
      $data[] = $datarow;

      $resultset = array(
                    "success"   =>  true,
                    "page"      => 1,
                    "pagecount" => 1,
                    "rowcount"  => 1,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );
    } else {
      $resultset = array(
                    "success"   =>  true,
                    "page"      => 1,
                    "pagecount" => 1,
                    "rowcount"  => 0,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => array()
      );
    }
    $returnvalue = $resultset;

    return $returnvalue;
  }


  //functions (array)
  function json_rows_array($result, $rowcount=-1, $xmldefault=false, $page=0, $buffersize=50, $primaryfieldname=false) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    //init variables
    $data               = [];
    $datarow            = [];
    $defaultcopy        = [];
    $defaultfieldnames  = [];
    $defaultfieldvalues = [];
    $pagecount          = 0;
    $tagname            = "";

    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, "record")) {
          foreach ($record->children() as $field) {
            $defaultcopy[]        = strtobool((string)$field["copy"]);
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }

    if (is_array($result)
    && !empty($result)) {

      //rowcount
      if ($rowcount <= 0) {
        $rowcount = count($result);
      }

      //pagecount
      if ($buffersize > 0) {
        $pagecount  = ceil($rowcount / $buffersize);
      } else {
        $pagecount  = 1;
      }

      $data    = array();
      foreach ($result as $rowkey=>$row) {
        if (is_array($row)) {
          if (is_numeric($rowkey)) {
            $datarow = forceUTF8var($row);
          } else {
            $datarow[$rowkey] = $row;
          }

          /*
          $datarow=array();
          foreach ($row as $key=>$value) {
            $datarow[$key] = forceUTF8var($value);

              $fieldname = $key;
              $keyindex = array_search($fieldname, $defaultfieldnames);

              if ($keyindex !== FALSE) {
                if (isempty($value) || !$defaultcopy[$keyindex]) {
                  $datarow[$fieldname] = forceUTF8var($defaultfieldvalues[$keyindex]);
                } else {
                  $datarow[$fieldname] = forceUTF8var($value);
                }
              } else {
                //if ($value == "TRUE") {
                //  $datarow[$fieldname] = true;
                //} elseif ($value == "FALSE") {
                //  $datarow[$fieldname] = false;
                //} else {
                  //$datarow[$fieldname] = convert_to_utf8($value);
                 $datarow[$fieldname] = forceUTF8var($value);
                //}
              }
            }
          }
          */
        }

        if ($primaryfieldname) {
          $primaryfieldvalue = $datarow[$primaryfieldname];
          $data[$primaryfieldvalue] = $datarow;
        } else if (is_numeric($rowkey)) {
          $data[] = $datarow;
        } else {
          $data = $datarow;
        }
      }

      $resultset = array(
                    "success"   =>  true,
                    "page"      => $page,
                    "pagecount" => $pagecount,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  //functions (array)
  function json_rows_clean($result, $rowcount=-1, $xmldefault=false, $page=0, $buffersize=50, $primaryfieldname=false) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    //init variables
    $data               = [];
    $datarow            = [];
    $defaultcopy        = [];
    $defaultfieldnames  = [];
    $defaultfieldvalues = [];
    $pagecount          = 0;
    $tagname            = "";

    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, "record")) {
          foreach ($record->children() as $field) {
            $defaultcopy[]        = strtobool((string)$field["copy"]);
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }

    if (is_array($result)
        && !empty($result)) {

          //rowcount
          if ($rowcount <= 0) {
            $rowcount = count($result);
          }

          //pagecount
          if ($buffersize > 0) {
            $pagecount  = ceil($rowcount / $buffersize);
          } else {
            $pagecount  = 1;
          }

          $data    = array();
          foreach ($result as $rowkey=>$row) {
            if (is_array($row)) {
              if (is_numeric($rowkey)) {
                $datarow = forceUTF8var($row);
              } else {
                $datarow[$rowkey] = $row;
              }

              /*
               $datarow=array();
               foreach ($row as $key=>$value) {
               $datarow[$key] = forceUTF8var($value);

               $fieldname = $key;
               $keyindex = array_search($fieldname, $defaultfieldnames);

               if ($keyindex !== FALSE) {
               if (isempty($value) || !$defaultcopy[$keyindex]) {
               $datarow[$fieldname] = forceUTF8var($defaultfieldvalues[$keyindex]);
               } else {
               $datarow[$fieldname] = forceUTF8var($value);
               }
               } else {
               //if ($value == "TRUE") {
               //  $datarow[$fieldname] = true;
               //} elseif ($value == "FALSE") {
               //  $datarow[$fieldname] = false;
               //} else {
               //$datarow[$fieldname] = convert_to_utf8($value);
               $datarow[$fieldname] = forceUTF8var($value);
               //}
               }
               }
               }
               */
            }

            if ($primaryfieldname) {
              $primaryfieldvalue = $datarow[$primaryfieldname];
              $data[$primaryfieldvalue] = $datarow;
            } else if (is_numeric($rowkey)) {
              $data[] = $datarow;
            } else {
              $data = $datarow;
            }
          }

          $returnvalue = $data;
        }

        return $returnvalue;
  }


  //functions (array)
  function json_objects_array($result, $rowcount=-1, $page=0, $buffersize=50, $primaryfieldname=false) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    //init variables
    $data               = [];
    $datarow            = [];
    $pagecount          = 0;
    $tagname            = "";

    if (is_array($result)
    && !empty($result)) {
      //rowcount
      if ($rowcount <= 0) {
        $rowcount = count($result);
      }

      //pagecount
      if ($buffersize > 0) {
        $pagecount  = ceil($rowcount / $buffersize);
      } else {
        $pagecount  = 1;
      }

      $data    = array();
      foreach ($result as $objectkey=>$object) {
        if (is_object($object)) {
          $datarow = get_object_vars($object);
        }

        $data[] = $datarow;
      }

      $resultset = array(
                    "success"   =>  true,
                    "page"      => $page,
                    "pagecount" => $pagecount,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }



  function json_fields_array($result, $includeemptyfields=true) {
    //levert array van alle FIELDS in een sql result
    $returnvalue=false;

    if (is_array($result)
    &&  !empty($result)) {
      //rowcount
      $rowcount = 0;

      $data    = array();
      foreach ($result as $row) {
        $datarow=array();
        foreach ($row as $key=>$value) {
          if (!is_numeric($key)) {
            if ($includeemptyfields
            ||  !is_empty($value)) {
              $datarow["FieldName"]  = $key;
              $datarow["FieldValue"] = forceUTF8($value);

              $data[] = $datarow;

              $rowcount++;
            }
          }
        }
      }

      $resultset = array(
                    "success"   =>  true,
                    "page"      => 1,
                    "pagecount" => 1,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  function json_json_array($result, $jsonfieldname=false, $displayfieldname=false) {
    //levert array van JSON in een sql result
    $returnvalue=false;

    if (is_array($result)
    &&  !empty($result)) {
      //rowcount
      $rowcount = 0;

      $data    = array();
      foreach ($result as $row) {
        $datarow=array();
        foreach ($row as $key=>$value) {
          if (!is_numeric($key)) {
            if ($key == $jsonfieldname
            ||  !$jsonfieldname) {
              $data = json_decode($value, true);

              $rowcount++;
            }
          }
        }
      }

      if ($displayfieldname) {
        foreach ($data as $datakey=>$datarow) {
          if (isset($datarow[$displayfieldname])
          &&  comparetext($datarow[$displayfieldname], "false")) {
            unset($data[$datakey]);
            $rowcount--;
          }
        }
        $data = array_values($data);
      }

      $resultset = array(
                    "success"   =>  true,
                    "page"      => 1,
                    "pagecount" => 1,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  function json_fields_empty($result, $includeemptyfields=true) {
    //levert array van alle FIELDS in een sql result
    $returnvalue=false;

    $data      = array();

    $resultset = array(
                    "success"   =>  true,
                    "page"      => 1,
                    "pagecount" => 1,
                    "rowcount"  => 0,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
    );

    $returnvalue = $resultset;

    return $returnvalue;
  }


  function json_rows_associative_array($result, $primaryfieldname=false) {
    return json_rows_array($result, -1, false, 0, 50, $primaryfieldname);
  }


  function json_row_array($result, $rowcount=-1, $xmldefault=false) {
    //levert array van eerste row in een adodb result
    $returnvalue=false;

    $defaultfieldnames = array();
    $defaultfieldvalues= array();

    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, 'record')) {
          foreach ($record->children() as $field) {
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }

    if ($result) {
      $row      = $result[0];

      $data = array();
      foreach ($row as $key=>$value) {
        if (!is_numeric($key)) {
	          $fieldname = $key;
	          $keyindex = array_search($fieldname, $defaultfieldnames);

	          if ($keyindex !== FALSE) {
	            $data[$fieldname] = str_ireplace('"', '', $defaultfieldvalues[$keyindex]);
	          } else {
              //$data[$fieldname] = convert_to_utf8($value);
	           $data[$fieldname] = $value;
            }
        }
      }

      $resultset = array(
                    "success"   =>  true,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
                  );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  /*
  function json_tree_array($result, $rowcount=-1, $primaryfieldname, $parentfieldname) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    if ($result) {
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      //array met parent ids samenstellen
      $parentids = array();
      foreach ($result as $row) {
        if ($row[$parentfieldname] != '0') {
          $parentids[$row[$parentfieldname]][] = $row[$primaryfieldname];
        }
      }

      $data    = array();
      foreach ($result as $row) {
        $datarow=array();
        foreach ($row as $key=>$value) {
          $datarow[$key] = $value;
        }

        if (array_key_exists_case($row[$parentfieldname], $parentids)) {
          $datarow["leaf"] = true;
          $data[$row[$parentfieldname]]["children"][] = $datarow;
        } else {
          $datarow["leaf"] = false;
          $data[$row[$primaryfieldname]] = $datarow;
        }
      }

      $resultset=array();
      foreach ($data as $key=>$value) {
        $resultset[] = $data[$key];
      }

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }
  */


  //CHART
  function json_chart_array($result, $rowcount=-1, $amountfieldname, $groupingfieldname1, $groupingfieldname2, $totalfieldname) {
    $timerstart = timer_start();

    //levert array van alle rows in een sql result
    $returnvalue=false;

    if ($result) {
      //rowcount
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      //initialize arrays
      $data       = array();
      $ticks      = array();
      $series     = array();
      $amounts    = array();
      $miscs      = array(array());

      foreach ($result as $row) {
      	$tick = $row[$groupingfieldname1];
        if (is_array($ticks) && !in_array($tick, $ticks)) {
          $ticks[]  = $tick;
        }
      }

      foreach ($result as $row) {
        $serie = $row[$groupingfieldname2];
        if (is_array($series) && !in_array($serie, $series)) {
          $series[] = $serie;
        }
      }

      foreach ($result as $row) {
        foreach ($row as $fieldname=>$fieldvalue) {
          if ($fieldname != $groupingfieldname1
          &&  $fieldname != $groupingfieldname2) {
            $misc = $row[$fieldname];
            if (isset($miscs[$fieldname])) {
              if (is_array($miscs[$fieldname]) && !in_array($misc, $miscs[$fieldname])) {
                $miscs[$fieldname][] = $misc;
              }
            } else {
              $miscs[$fieldname][] = $misc;
            }
          }
        }
      }

      //timing
      //fb_timer_end($timerstart, 0, "json_chart_array series");
      //hoeveel groeperingen zijn er ?
      if (count($ticks) > 0
      &&  count($series) == 1) {
        //1 groepering
        foreach ($result as $row) {
          $datarow  = array();

          $datarow[$groupingfieldname1] = $row[$groupingfieldname1];
          $datarow[$amountfieldname]    = $row[$amountfieldname];

          //overige velden
          foreach ($row as $fieldname=>$fieldvalue) {
            $datarow[$fieldname]        = forceUTF8($fieldvalue);
          }

          $data[] = $datarow;
        }

      } elseif (count($ticks) > 0
            &&  count($series) > 1) {
        //2 groeperingen
        foreach ($result as $row) {
          //if (isset($amounts[$row[$groupingfieldname1]])) {
          if (isset($row[$groupingfieldname1])
          &&  isset($row[$groupingfieldname2])
          &&  isset($row[$amountfieldname])) {
            //init ammounts array to prevent notices
            if (!isset($amounts[$row[$groupingfieldname1]])) {
              $amounts[$row[$groupingfieldname1]] = array();
            }
            if (!isset($amounts[$row[$groupingfieldname1]][$row[$groupingfieldname2]])) {
              $amounts[$row[$groupingfieldname1]][$row[$groupingfieldname2]] = 0;
            }

            //set ammounts array
            $amounts[$row[$groupingfieldname1]][$row[$groupingfieldname2]] += $row[$amountfieldname];
          }
        }

        foreach ($ticks as $tickkey=>$tick) {
          $datarow[$groupingfieldname1]    =  $tick;

          $total  = 0;
          foreach ($series as $serie) {
            if (isset($amounts[$tick])
            &&  isset($amounts[$tick][$serie])) {
              $datarow[$serie] = $amounts[$tick][$serie];
              $total           += $amounts[$tick][$serie];
            }
          }

          //percentages
          foreach ($series as $serie) {
            if (isset($amounts[$tick])
            &&  isset($amounts[$tick][$serie])) {
              $datarow[$serie . "_perc"]    =  (round(($amounts[$tick][$serie] / max($total,1)) * 100));
            }
          }

          //overige velden
          foreach ($miscs as $misckey=>$misc) {
            if (isset($miscs[$misckey])
            &&  isset($miscs[$misckey][$tickkey])) {
              $datarow[$misckey] = $miscs[$misckey][$tickkey];
            }
          }

          //totaal
          $datarow[$amountfieldname]    =  $total;

          $data[] = $datarow;
        }

/*
        foreach ($ticks as $tick) {
          $datarow  = array();
          $datarow[$groupingfieldname1]    =  $tick;

          //groepwaardes
          $total  = 0;
          foreach ($series as $serie) {
            foreach ($result as $row) {
              if ($row[$groupingfieldname1] == $tick
              &&  $row[$groupingfieldname2] == $serie) {
                $amounts[$tick][$serie] = $row[$amountfieldname];
                $total                  = $total + $row[$amountfieldname];
              }
            }

            $datarow[$serie]            =  $amounts[$tick][$serie];
          }

          //percentages
          foreach ($series as $serie) {
            $datarow[$serie . "_perc"]    =  (round(($amounts[$tick][$serie] / $total) * 100));
          }

          //totaal
          $datarow[$amountfieldname]    =  $total;

          $data[] = $datarow;
        }
*/
      }

      $resultset = array(
                    "success"   => true,
                    "page"      => 0,
                    "pagecount" => 0,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
                   );

      $returnvalue  = $resultset;
    }

    //timing
    //fb_timer_end($timerstart, 0, "json_chart_array");

    return $returnvalue;
  }


  //CALENDAR
  function json_calendar_array($result, $rowcount=-1, $primaryfieldname, $titelfieldname, $datestartfieldname, $dateendfieldname, $iconfieldname="") {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    if ($result) {
      //rowcount
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      $data = array();

      foreach ($result as $row) {
        $datarow  = array();

        $datestartparts = array_key_exists($datestartfieldname, $row) ? explode(" ", $row[$datestartfieldname]) : array();
        $timestart      = (count($datestartparts) > 1) ? $datestartparts[1] : "";

        $dateendparts   = array_key_exists($dateendfieldname, $row) ? explode(" ", $row[$dateendfieldname]) : array();
        $timeend        = (count($dateendparts) > 1) ? $dateendparts[1] : "";

        if (true)                     {$datarow["id"]       = array_key_exists($primaryfieldname, $row)   ? $row[$primaryfieldname] : "";}
        if (true)                     {$datarow["title"]    = array_key_exists($titelfieldname, $row)     ? $row[$titelfieldname] : "";}
        if (true)                     {$datarow["start"]    = array_key_exists($datestartfieldname, $row) ? trim($row[$datestartfieldname]) : "";}
        if (true)                     {$datarow["end"]      = array_key_exists($dateendfieldname, $row)   ? trim($row[$dateendfieldname]) : "";}
        if (true)                     {$datarow["allDay"]   = ($timestart) ? false : true;}
        if (true)                     {$datarow["icon"]     = array_key_exists($iconfieldname, $row)      ? $row[$iconfieldname] : "";}
        //if ($row[$dateendfieldname]){$datarow["enddate"]  = $row[$dateendfieldname];}

        foreach ($row as $key=>$value) {
          if (!is_numeric($key)) {
            $fieldname = $key;

            $datarow[$fieldname] = forceUTF8($value);
          }
        }

        $data[] = $datarow;
      }

      $resultset = $data;

      $returnvalue  = $resultset;
    }

    return $returnvalue;
  }


  //GANTT
  function json_gantt_array($result, $rowcount=-1, $primaryfieldname, $titelfieldname, $datestartfieldname, $dateendfieldname, $iconfieldname="") {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    if ($result) {
      //rowcount
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      $data = array();

      foreach ($result as $row) {
        $datarow  = array();

        $datestartparts = array_key_exists($datestartfieldname, $row) ? explode(" ", $row[$datestartfieldname]) : array();
        $timestart      = (count($datestartparts) > 1) ? $datestartparts[1] : "";

        $dateendparts   = array_key_exists($dateendfieldname, $row) ? explode(" ", $row[$dateendfieldname]) : array();
        $timeend        = (count($dateendparts) > 1) ? $dateendparts[1] : "";

        if (true)                     {$datarow["id"]       = array_key_exists($primaryfieldname, $row)   ? $row[$primaryfieldname] : "";}
        if (true)                     {$datarow["label"]    = array_key_exists($titelfieldname, $row)     ? $row[$titelfieldname] : "";}
        if (true)                     {$datarow["to"]       = array_key_exists($datestartfieldname, $row) ? trim($row[$datestartfieldname]) : "";}
        if (true)                     {$datarow["from"]      = array_key_exists($dateendfieldname, $row)   ? trim($row[$dateendfieldname]) : "";}

        foreach ($row as $key=>$value) {
          if (!is_numeric($key)) {
            $fieldname = $key;

            $datarow[$fieldname] = forceUTF8($value);
          }
        }

        $data[] = $datarow;
      }

      $resultset = $data;

      $returnvalue  = $resultset;
    }

    return $returnvalue;
  }


  //TREE
  function json_tree_array($result, $rowcount=-1, $primaryfieldname, $parentfieldname) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    if ($result) {
      //rowcount
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      //tree
      $tree = new Tree();
      foreach ($result as $row) {
        //$tree->addNode($row[$primaryfieldname], $row, $row[$parentfieldname]);
        $tree->addNode($row[$primaryfieldname], $row, $row[$parentfieldname]);
      }

      $returnvalue = $tree->getArray();
    }

    return $returnvalue;
  }


  //functions (array)
  function json_columns_array($result, $rowcount=-1, $xmlcolumns=false, $page=0, $buffersize=50) {
    //levert array van alle columns in een sql result
    $returnvalue=false;

    $columnfieldnames = array();

    //columns uit xml halen
    if ($xmlcolumns) {
      foreach ($xmlcolumns->children() as $grid) {
        $tagname = $grid->getName();
        if (comparetext($tagname, "grid")) {
          $primaryfieldname = (string)$grid["primaryfieldname"];

          foreach ($grid->children() as $column) {
            $tagname = $column->getName();

            if (comparetext($tagname, "column")) {
              $columnfieldnames[] = (string)$column["fieldname"];
            }
          }
        }
      }
    }

    //result aflopen en in array stoppen
    if ($result) {
      //rowcount
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      //pagecount
      $pagecount = ceil($rowcount / $buffersize);

      $data    = array();
      foreach ($result as $row) {
        $datarow=array();

        if (array_key_exists_case($primaryfieldname, $row)) {
          $datarow["id"]   = $row[$primaryfieldname];
          $datarow["cell"] = array();
        }

        foreach ($columnfieldnames as $key=>$columnfieldname) {
          if (array_key_exists_case($columnfieldname, $row)) {
            $datarow["cell"][] = $row[$columnfieldname];
          }
        }

        $data[] = $datarow;
      }

      $resultset = array(
                    "success"   => true,
                    "page"      => $page,
                    "pagecount" => $pagecount,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
                   );

      $returnvalue = $resultset;
    } else {
      $resultset = array(
                    "success"   => true,
                    "page"      => 0,
                    "pagecount" => 0,
                    "rowcount"  => 0,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => array(),
                   );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  function json_rows_xml($xml) {
    //levert array van alle records in een xml
    $returnvalue=false;
    $data     = array();
    $datarow  = array();
    $rowcount = 0;

    if ($xml) {
      foreach ($xml->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, 'record')) {
          $rowcount++;

          $datarow["tablename"]         = (string)$record['tablename'];
          $datarow["primaryfieldname"]  = (string)$record['primaryfieldname'];
          $datarow["primaryfieldvalue"] = (string)$record['primaryfieldvalue'];

          foreach ($record->children() as $field) {
            $fieldname    = (string)$field["fieldname"];
            $fieldtype    = (string)$field["type"];
            $fieldvalue   = (string)$field;

            $datarow[$fieldname] = $fieldvalue;
          }

          $data[] = $datarow;
        }
      }

      $resultset = array(
                    "success"   => true,
                    "rowcount"  => "$rowcount",
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
                   );
    } else {
      $resultset = array(
                    "success"   => false,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "message"   => "no fields submitted"
                   );
    }

    $returnvalue = $resultset;

    return $returnvalue;
  }


  //Pretty print some JSON
  function json_format($json) {
    $tab = "  ";
    $new_json = "";
    $indent_level = 0;
    $in_string = false;

    $json_obj = json_decode($json);

    if($json_obj === false)
        return false;

    $json = json_encode($json_obj);
    $len = strlen($json);

    for($c = 0; $c < $len; $c++)
    {
        $char = $json[$c];
        switch($char)
        {
            case '{':
            case '[':
                if(!$in_string)
                {
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
                    $indent_level++;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '}':
            case ']':
                if(!$in_string)
                {
                    $indent_level--;
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ',':
                if(!$in_string)
                {
                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ':':
                if(!$in_string)
                {
                    $new_json .= ": ";
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '"':
                if($c > 0 && $json[$c-1] != '\\')
                {
                    $in_string = !$in_string;
                }
            default:
                $new_json .= $char;
                break;
        }
    }

    return $new_json;
  }


function json_last_error_string() {
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            return  ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            return  ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            return  ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            return  ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            return  ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            return  ' - Unknown error';
        break;
    }
}


  //functions (array)
  function json_gridrowscols_array($result, $rowdisplayfieldname, $columndisplayfieldname, $celldisplayfieldname, $rowvaluefieldname, $columnvaluefieldname, $cellvaluefieldname, $page=0, $buffersize=50) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    //init variables
    $colcount           = 0;
    $colfieldvalue      = "";
    $colindex           = -1;
    $cols               = [];
    $data               = [];
    $datarow            = [];
    $datarowcol         = [];
    $defaultfieldnames  = [];
    $defaultfieldvalues = [];
    $pagecount          = 0;
    $rowcount           = 0;
    $rowfieldvalue      = "";
    $rowindex           = -1;
    $rows               = [];

    /*
    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, "record")) {
          foreach ($record->children() as $field) {
            $defaultcopy[]        = strtobool((string)$field["copy"]);
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }
    */

    if (is_array($result)
    && !empty($result)) {
      //pagecount
      if ($buffersize > 0) {
        $pagecount  = ceil($rowcount / $buffersize);
      } else {
        $pagecount  = 1;
      }

      foreach ($result as $record) {
        $colcount = 0;

        $rowdisplayfieldvalue     = $record[$rowdisplayfieldname];
        $rowvaluefieldvalue       = $record[$rowvaluefieldname];
        $columndisplayfieldvalue  = $record[$columndisplayfieldname];
        $columnvaluefieldvalue    = $record[$columnvaluefieldname];
        $celldisplayfieldvalue    = $record[$celldisplayfieldname];
        $cellvaluefieldvalue      = $record[$cellvaluefieldname];

        //$cols[$rowfieldvalue]  = $rowfieldvalue;

        $rows[$rowdisplayfieldvalue] = $rowdisplayfieldvalue;
        $cols[$columndisplayfieldvalue] = $columndisplayfieldvalue;

        if (!array_key_exists($rowdisplayfieldvalue, $datarowcol)
        ||  !array_key_exists($columndisplayfieldvalue, $datarowcol[$rowdisplayfieldvalue])
        ||  is_empty($datarowcol[$rowdisplayfieldvalue][$columndisplayfieldvalue])) {
          $datarowcol[$rowdisplayfieldvalue][$columndisplayfieldvalue] = "{" . $celldisplayfieldname . ":" . $celldisplayfieldvalue . " , " . $cellvaluefieldname . ":" . $cellvaluefieldvalue . "}";
        } else {
          //TODO: gebruik SeparatorCount als er meerdere values in een cel zitten
          $datarowcol[$rowdisplayfieldvalue][$columndisplayfieldvalue] .= "<br />" . $celldisplayfieldvalue;
        }

        $rowcount++;
      }

      //sort
      ksort($cols);
      ksort($rows);

      //data
      foreach ($rows as $rowkey=>$row) {
        $datarow  = [];
        $datarow[$rowdisplayfieldname] = $row;

        foreach ($cols as $colkey=>$col) {
          if (array_key_exists($rowkey, $datarowcol)
          &&  array_key_exists($colkey, $datarowcol[$rowkey])) {
            $datarow[$col]  = $datarowcol[$rowkey][$colkey];
          } else {
            $datarow[$col]  = "";
          }
        }

        $data[] = $datarow;
      }

      $resultset = array(
                    "success"   =>  true,
                    "page"      => $page,
                    "pagecount" => $pagecount,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }


  //functions (array)
  function json_table_array($result, $rowfieldname, $columnfieldname, $cellfieldname, $valuefieldname, $page=0, $buffersize=50) {
    //levert array van alle rows in een sql result
    $returnvalue=false;

    //init variables
    $colcount           = 0;
    $colfieldvalue      = "";
    $colindex           = -1;
    $cols               = [];
    $data               = [];
    $datarow            = [];
    $datarowcol         = [];
    $defaultfieldnames  = [];
    $defaultfieldvalues = [];
    $pagecount          = 0;
    $rowcount           = 0;
    $rowfieldvalue      = "";
    $rowindex           = -1;
    $rows               = [];

    /*
    //defaults uit xml halen
    if ($xmldefault) {
      foreach ($xmldefault->children() as $record) {
        $tagname = $record->getName();

        if (comparetext($tagname, "record")) {
          foreach ($record->children() as $field) {
            $defaultcopy[]        = strtobool((string)$field["copy"]);
            $defaultfieldnames[]  = (string)$field["fieldname"];
            if ((string)$field != "{null}" && (string)$field != "null") {
              $defaultfieldvalues[] = (string)$field;
            } else {
              $defaultfieldvalues[] = "";
            }
          }
        }
      }
    }
    */

    if (is_array($result)
    && !empty($result)) {
      //pagecount
      if ($buffersize > 0) {
        $pagecount  = ceil($rowcount / $buffersize);
      } else {
        $pagecount  = 1;
      }

      foreach ($result as $record) {
        $colcount = 0;

        $rowfieldvalue = $record[$rowfieldname];
        $columnfieldvalue = $record[$columnfieldname];
        $cellfieldvalue = $record[$cellfieldname];
        $valuefieldvalue = $record[$valuefieldname];

        //$cols[$rowfieldvalue]  = $rowfieldvalue;

        $rows[$rowfieldvalue] = $rowfieldvalue;
        $cols[$columnfieldvalue] = $columnfieldvalue;

        if (!array_key_exists($rowfieldvalue, $datarowcol)
        ||  !array_key_exists($columnfieldvalue, $datarowcol[$rowfieldvalue])
        ||  is_empty($datarowcol[$rowfieldvalue][$columnfieldvalue])) {
          $datarowcol[$rowfieldvalue][$columnfieldvalue] = $cellfieldvalue;
        } else {
          //TODO: gebruik SeparatorCount als er meerdere values in een cel zitten
          $datarowcol[$rowfieldvalue][$columnfieldvalue] .= "<br />" . $cellfieldvalue;
        }

        $rowcount++;
      }

      //sort
      ksort($cols);
      ksort($rows);

      //data
      foreach ($rows as $rowkey=>$row) {
        $datarow  = [];
        $datarow[$rowfieldname] = $row;

        foreach ($cols as $colkey=>$col) {
          if (array_key_exists($rowkey, $datarowcol)
          &&  array_key_exists($colkey, $datarowcol[$rowkey])) {
            $datarow[$col]  = $datarowcol[$rowkey][$colkey];
          } else {
            $datarow[$col]  = "";
          }
        }

        $data[] = $datarow;
      }

      $resultset = array(
                    "success"   =>  true,
                    "page"      => $page,
                    "pagecount" => $pagecount,
                    "rowcount"  => $rowcount,
                    "datetime"  => date("Y/m/d H:i:s"),
                    "data"      => $data
      );

      $returnvalue = $resultset;
    }

    return $returnvalue;
  }

/*
  function json_tree_array($result, $rowcount=-1, $primaryfieldname, $parentfieldname) {
    $tree = "";         // Clear the directory tree
    $depth = 1;         // Child level depth.
    $top_level_on = 0;  // What top-level category are we on?
    $exclude = array();       // Define the exclusion array
    array_push($exclude, 0);  // Put a starting value in it

    if ($result) {
      if ($rowcount == -1) {
        $rowcount = count($result);
      }

      foreach ($result as $row) {
        $goOn = 1;      // Resets variable to allow us to continue building out the tree.
        for($x = 0; $x < count($exclude); $x++ ) {
          // Check to see if the new item has been used
          if ( $exclude[$x] == $row[$primaryfieldname] ) {
            $goOn = 0;
            break;        // Stop looking b/c we already found that it's in the exclusion list and we can't continue to process this node
          }
        }

        if ( $goOn == 1 )  {
          $datarow = array();
		      foreach ($row as $key=>$value) {
		        if (!is_numeric($key)) {
		            $fieldname = $key;
		            $datarow[$fieldname] = $value;
		        }
		      }
		      $data[] = $datarow;

		      array_push($exclude, $row[$primaryfieldname]);    // Add to the exclusion list

          if ( $row[$primaryfieldname] < 6 ) {
            $top_level_on = $row[$primaryfieldname];
          }

          $data[] = json_tree_array_child($row[$primaryfieldname]);    // Start the recursive function of building the child tree
        }
      }
    }
}

  function json_tree_array_child($result, $oldID)      // Recursive function to get all of the children...unlimited depth
		{
		  global $exclude, $depth;      // Refer to the global array defined at the top of this script

		  foreach ($result as $row) {
		  	if ($row[$primaryfieldname] == $oldID)
		  $child_query = mysql_query("SELECT * FROM `categories` WHERE parent_id=" . $oldID);
		  while ( $child = mysql_fetch_array($child_query) )
		  {
		    if ( $child['category_id'] != $child['parent_id'] )
		    {
		      for ( $c=0;$c<$depth;$c++ )     // Indent over so that there is distinction between levels
		      { $tempTree .= "&nbsp;"; }
		      $tempTree .= "- " . $child['title'] . "<br>";
		      $depth++;   // Incriment depth b/c we're building this child's child tree  (complicated yet???)
		      $tempTree .= build_child($child['category_id']);    // Add to the temporary local tree
		      $depth--;   // Decrement depth b/c we're done building the child's child tree.
		      array_push($exclude, $child['category_id']);      // Add the item to the exclusion list
		    }
		  }

		  return $tempTree;   // Return the entire child tree
		}
*/


  /* JSONPath 0.8.3 - XPath for JSON
   *
   * Copyright (c) 2007 Stefan Goessner (goessner.net)
   * Licensed under the MIT (MIT-LICENSE.txt) licence.
   */

  // API function
  function jsonPath($obj, $expr, $args=null) {
     $jsonpath = new JsonPath();
     $jsonpath->resultType = ($args ? $args['resultType'] : "VALUE");
     $x = $jsonpath->normalize($expr);
     $jsonpath->obj = $obj;
     if ($expr && $obj && ($jsonpath->resultType == "VALUE" || $jsonpath->resultType == "PATH")) {
        $jsonpath->trace(preg_replace("/^\\$;/", "", $x), $obj, "$");
        if (count($jsonpath->result))
           return $jsonpath->result;
        else
           return false;
     }
  }


  // JsonPath class (internal use only)
  class JsonPath {
     var $obj = null;
     var $resultType = "Value";
     var $result = array();
     var $subx = array();

     // normalize path expression
     function normalize($x) {
        $x = preg_replace_callback(array("/[\['](\??\(.*?\))[\]']/", "/\['(.*?)'\]/"), array(&$this, "_callback_01"), $x);
        $x = preg_replace(array("/'?\.'?|\['?/", "/;;;|;;/", "/;$|'?\]|'$/"),
                          array(";", ";..;", ""),
                          $x);
        $x = preg_replace_callback("/#([0-9]+)/", array(&$this, "_callback_02"), $x);
        $this->result = array();  // result array was temporarily used as a buffer ..
        return $x;
     }
     function _callback_01($m) { return "[#".(array_push($this->result, $m[1])-1)."]"; }
     function _callback_02($m) { return $this->result[$m[1]]; }

     function asPath($path) {
        $x = explode(";", $path);
        $p = "$";
        for ($i=1,$n=count($x); $i<$n; $i++)
           $p .= preg_match("/^[0-9*]+$/", $x[$i]) ? ("[".$x[$i]."]") : ("['".$x[$i]."']");
        return $p;
     }
     function store($p, $v) {
        if ($p) array_push($this->result, ($this->resultType == "PATH" ? $this->asPath($p) : $v));
        return !!$p;
     }
     function trace($expr, $val, $path) {
        if ($expr !== "") {
           $x = explode(";", $expr);
           $loc = array_shift($x);
           $x = implode(";", $x);

           if (is_array($val) && array_key_exists($loc, $val))
              $this->trace($x, $val[$loc], $path.";".$loc);
           else if ($loc == "*")
              $this->walk($loc, $x, $val, $path, array(&$this, "_callback_03"));
           else if ($loc === "..") {
              $this->trace($x, $val, $path);
              $this->walk($loc, $x, $val, $path, array(&$this, "_callback_04"));
           }
           else if (preg_match("/^\(.*?\)$/", $loc)) // [(expr)]
              $this->trace($this->evalx($loc, $val, substr($path,strrpos($path,";")+1)).";".$x, $val, $path);
           else if (preg_match("/^\?\(.*?\)$/", $loc)) // [?(expr)]
              $this->walk($loc, $x, $val, $path, array(&$this, "_callback_05"));
           else if (preg_match("/^(-?[0-9]*):(-?[0-9]*):?(-?[0-9]*)$/", $loc)) // [start:end:step]  phyton slice syntax
              $this->slice($loc, $x, $val, $path);
           else if (preg_match("/,/", $loc)) // [name1,name2,...]
              for ($s=preg_split("/'?,'?/", $loc),$i=0,$n=count($s); $i<$n; $i++)
                  $this->trace($s[$i].";".$x, $val, $path);
        }
        else
           $this->store($path, $val);
     }
     function _callback_03($m,$l,$x,$v,$p) { $this->trace($m.";".$x,$v,$p); }
     function _callback_04($m,$l,$x,$v,$p) { if (is_array($v[$m])) $this->trace("..;".$x,$v[$m],$p.";".$m); }
     function _callback_05($m,$l,$x,$v,$p) { if ($this->evalx(preg_replace("/^\?\((.*?)\)$/","$1",$l),$v[$m])) $this->trace($m.";".$x,$v,$p); }

     function walk($loc, $expr, $val, $path, $f) {
        foreach($val as $m => $v)
           call_user_func($f, $m, $loc, $expr, $val, $path);
     }
     function slice($loc, $expr, $v, $path) {
        $s = explode(":", preg_replace("/^(-?[0-9]*):(-?[0-9]*):?(-?[0-9]*)$/", "$1:$2:$3", $loc));
        $len=count($v);
        $start=(int)$s[0]?$s[0]:0;
        $end=(int)$s[1]?$s[1]:$len;
        $step=(int)$s[2]?$s[2]:1;
        $start = ($start < 0) ? max(0,$start+$len) : min($len,$start);
        $end   = ($end < 0)   ? max(0,$end+$len)   : min($len,$end);
        for ($i=$start; $i<$end; $i+=$step)
           $this->trace($i.";".$expr, $v, $path);
     }
     function evalx($x, $v, $vname) {
        $name = "";
        $expr = preg_replace(array("/\\$/","/@/"), array("\$this->obj","\$v"), $x);
        $res = eval("\$name = $expr;");

        if ($res === FALSE)
           print("(jsonPath) SyntaxError: " . $expr);
        else
           return $name;
     }
  }


function fixJSON($json) {
    $regex = <<<'REGEX'
~
    "[^"\\]*(?:\\.|[^"\\]*)*"
    (*SKIP)(*F)
  | '([^'\\]*(?:\\.|[^'\\]*)*)'
~x
REGEX;

    return preg_replace_callback($regex, function($matches) {
        return '"' . preg_replace('~\\\\.(*SKIP)(*F)|"~', '\\"', $matches[1]) . '"';
    }, $json);
}


function json_setobject($object, $json) {
  $returnvalue  = false;

  if (is_string($json)) {
    $json = json_decode($json, true);
  }
  if (!is_array($json)) {
    $json = [$json];
  }

  foreach ($json as $key=>$value) {
    if (is_object($object)) {
      if (method_exists($object, "getPropertyName")) {
        $jsonpropertyname = $object->getPropertyName($key, "");
      } else {
        $jsonpropertyname = getPropertyName($object, $key);
      }
    } else {
      $jsonpropertyname = null;
    }

    if (!is_empty($jsonpropertyname)) {
      //object
      switch (gettype($object->$jsonpropertyname)) {
        case "object" :
          $returnvalue = json_setobject($object->$jsonpropertyname, $value);
          break;

        case "array"  :
          //TODO: JSON arrays zijn altijd numeriek dus nooit associatief!
          //      (oplossen met iets als "$Arraynaam_primaryfieldname")
          //      arrays van objecten maken objecten aan van het type $Arraynaam_classname
          $object->$jsonpropertyname = [];
          foreach ($value as $arrkey=>$arrvalue) {
            $arrobject = null;
            if (is_array($arrvalue)) {
              $classname = $jsonpropertyname."_classname";

              if (property_exists($object,$classname)) {
                $newclassname = $object->$classname;
                $arrobject = new $newclassname();
              } else {
                $arrobject = new SB_Object();
              }
              json_setobject($arrobject, $arrvalue);
            } else {
              $arrobject = $arrvalue;
            }
            if (!is_null($arrobject)) {
              array_push($object->$jsonpropertyname, $arrobject);
            }
          }

          $returnvalue = $object->$jsonpropertyname;
          break;

        default       :
          $object->$jsonpropertyname  = $value;
          $returnvalue = $value;
          break;
      }
    } else {
      //properties uit de JSON moeten in principe altijd bestaan, onderstaande dus zoveel mogelijk voorkomen!
      $object->$key  = $value;
      $returnvalue = $value;
    }
  }

  return $returnvalue;
}


function json_setarray($arr, $json) {
  $jsonarr = json_decode_force($json, true);

  return array_merge($arr, $jsonarr);
}


function json_decode_force($jsonstr, $assoc = false) {
  //geeft altijd json object terug
  if (is_string($jsonstr)) {
    return json_decode($jsonstr, $assoc);
  } else {
    return $jsonstr;
  }
}


function json_encode_force($jsonobj) {
  //geeft altijd json string terug
  if (is_object($jsonobj)) {
    return json_encode($jsonobj);
  } else {
    return $jsonobj;
  }
}

function json_output_html( $json, $linebreak = "<br/>", $tab = "&nbsp;&nbsp;") {
  $result = '';
  $level = 0;
  $in_quotes = false;
  $in_escape = false;
  $ends_line_level = NULL;
  $json_length = strlen( $json );

  for( $i = 0; $i < $json_length; $i++ ) {
    $char = $json[$i];
    $new_line_level = NULL;
    $post = "";
    if( $ends_line_level !== NULL ) {
      $new_line_level = $ends_line_level;
      $ends_line_level = NULL;
    }
    if ( $in_escape ) {
      $in_escape = false;
    } else if( $char === '"' ) {
      $in_quotes = !$in_quotes;
    } else if( ! $in_quotes ) {
      switch( $char ) {
        case '}': case ']':
          $level--;
          $ends_line_level = NULL;
          $new_line_level = $level;
          break;

        case '{': case '[':
          $level++;
        case ',':
          $ends_line_level = $level;
          break;

        case ':':
          $post = " ";
          break;

        case " ": case "\t": case "\n": case "\r":
          $char = "";
          $ends_line_level = $new_line_level;
          $new_line_level = NULL;
          break;
      }
    } else if ( $char === '\\' ) {
      $in_escape = true;
    }
    if( $new_line_level !== NULL ) {
      $result .= $linebreak.str_repeat( $tab, $new_line_level );
    }
    $result .= $char.$post;
  }

  return $result;
}

?>
