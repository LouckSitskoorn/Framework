<?
  //NAMESPACE
  namespace SB\Functions;

  //FUNCTIONS
  function vandaag() {
    return date('d-m-Y');
  }

  function dagnaam($dagnummer) {
    if ($dagnummer=='1') {
      $maand = 'maandag';
    }
    if ($dagnummer=='2') {
      $maand = 'dinsdag';
    }
    if ($dagnummer=='3') {
      $maand = 'woensdag';
    }
    if ($dagnummer=='4') {
      $maand = 'donderdag';
    }
    if ($dagnummer=='5') {
      $maand = 'vrijdag';
    }
    if ($dagnummer=='6') {
      $maand = 'zaterdag';
    }
    if ($dagnummer=='7') {
      $maand = 'zondag';
    }

  }

  function maandnaam($maandnummer) {

    if ($maandnummer=='1'
    ||  $maandnummer=='01'
    ||  $maandnummer==1) {
      $maand = 'januari';
    }
    if ($maandnummer=='2'
    ||  $maandnummer=='02'
    ||  $maandnummer==2) {
      $maand = 'februari';
    }
    if ($maandnummer=='3'
    ||  $maandnummer=='03'
    ||  $maandnummer==3) {
      $maand = 'maart';
    }
    if ($maandnummer=='4'
    ||  $maandnummer=='04'
    ||  $maandnummer==4) {
      $maand = 'april';
    }
    if ($maandnummer=='5'
    ||  $maandnummer=='05'
    ||  $maandnummer==5) {
      $maand = 'mei';
    }
    if ($maandnummer=='6'
    ||  $maandnummer=='06'
    ||  $maandnummer==6) {
      $maand = 'juni';
    }
    if ($maandnummer=='7'
    ||  $maandnummer=='07'
    ||  $maandnummer==7) {
      $maand = 'juli';
    }
    if ($maandnummer=='8'
    ||  $maandnummer=='08'
    ||  $maandnummer==8) {
      $maand = 'augustus';
    }
    if ($maandnummer=='9'
    ||  $maandnummer=='09'
    ||  $maandnummer==9) {
      $maand = 'september';
    }
    if ($maandnummer=='10'
    ||  $maandnummer==10) {
      $maand = 'oktober';
    }
    if ($maandnummer=='11'
    ||  $maandnummer==11) {
      $maand = 'november';
    }
    if ($maandnummer=='12'
    ||  $maandnummer==12) {
      $maand = 'december';
    }
    return $maand;
  }

  function add_business_days($offset, $date) {
/*
    if ($offset <= 0)
        return $date; // Could relax this to allow for backwards counting?

    // First make sure our date is a business day
    $day_of_week = date('w', $date);
    if ($date_of_week == 6)
        $date += 86400 * 2;
    else if ($date_of_week == 0)
        $date += 86400;

    // Now do the calculation, first adding the weeks:
    $date += floor($offset / 5) * 86400 * 7;

    // And then adding the extra days:
    $date += ($offset % 5) * 86400;

    // And make sure we don't end on a weekend:
    $day_of_week = date('w', $date);
    if ($date_of_week == 6)
        $date += 86400 * 2;
    else if ($date_of_week == 0)
        $date += 86400;

    return $date;
  }
*/

  $evalday = $date + $offset * 86400; //mktime($date, strtotime("+$offset days"));
  $dayofweek = date('w', $evalday);
  if ($dayofweek == 0) {
    $evalday = $date + ($offset+1) * 86400;
  }
  if($dayofweek == 6) {
    $evalday = $date + ($offset+2) * 86400;
  }

  return $evalday;

/*
  for ($i=0;$i<$offset;$i++) {
    $evalday = mktime($date, strtotime("+$i days")));
    $theday = date('w', $evalday);
    if($theday != 0 and $theday != 6) {
      $days = $i;
//      $the_days[$j] = strftime("%A, %B %d, %Y", strtotime("+$jump days"));
      $j++;
    }
  }
*/
  }

  function maandnummerstr($maandnaam) {
    $returnvalue =false;

    $maandnaam = strtolower($maandnaam);

    if ($maandnaam=="januari"
    ||  $maandnaam=="jan") {
      $returnvalue = '01';
    }
    if ($maandnaam=="februari"
    ||  $maandnaam=="feb") {
      $returnvalue = '02';
    }
    if ($maandnaam=="maart"
    ||  $maandnaam=="mar") {
      $returnvalue = '03';
    }
    if ($maandnaam=="april"
    ||  $maandnaam=="apr") {
      $returnvalue = '04';
    }
    if ($maandnaam=="mei"
    ||  $maandnaam=="may") {
      $returnvalue = '05';
    }
    if ($maandnaam=="juni"
    ||  $maandnaam=="jun") {
      $returnvalue = '06';
    }
    if ($maandnaam=="juli"
    ||  $maandnaam=="jul") {
      $returnvalue = '07';
    }
    if ($maandnaam=="augustus"
    ||  $maandnaam=="aug") {
      $returnvalue = '08';
    }
    if ($maandnaam=="september"
    ||  $maandnaam=="sep") {
      $returnvalue = '09';
    }
    if ($maandnaam=="oktober"
    ||  $maandnaam=="oct") {
      $returnvalue = '10';
    }
    if ($maandnaam=="november"
    ||  $maandnaam=="nov") {
      $returnvalue = '11';
    }
    if ($maandnaam=="december"
    ||  $maandnaam=="dec") {
      $returnvalue = '12';
    }

    return $returnvalue;
  }

  function mysql_date($string) {
    if (comparetext($string, ["{submit:datetime}", "[submit:datetime]"])) {
      $string = date("Y/m/d H:i:s");
    } elseif (comparetext($string, ["{submit:datetimestring}", "[submit:datetimestring]"])) {
      $string = date("YmdHis");
    } elseif (comparetext($string, ["{submit:date}", "[submit:date]"])) {
      $string = date("Y/m/d");
    } elseif (comparetext($string, ["{submit:datestring}", "[submit:datestring]"])) {
      $string = date("Ymd");
    } elseif (comparetext($string, ["{submit:time}", "[submit:time]"])) {
      $string = date("H:i:s");
    } elseif (comparetext($string, ["{submit:timestring}", "[submit:timestring]"])) {
      $string = date("His");
    } elseif (stripos($string, "GMT") !== false) {
      $datevalues = explode(" ", $string);
      $string = $datevalues[3] . "/" . maandnummerstr($datevalues[1]) . "/" . $datevalues[2] . " " . $datevalues[4];
    } else if (stripos($string, "UTC") !== false) {
      $datevalues = explode(" ", $string);
      $string = $datevalues[5] . "/" . maandnummerstr($datevalues[1]) . "/" . $datevalues[2] . " " . $datevalues[4];
    } elseif (stripos($string, "-") !== false) {
      if (stripos($string, ":") !== false) {
        $dateparts  = explode(" ", $string);
        $datevalues = explode("-", $dateparts[0]);
        $timevalues = explode(":", $dateparts[1]);

        if ((integer)$datevalues[0] <= 1980) {
          $string = (isset($datevalues[2]) ? $datevalues[2] : date("Y")) . "/" . $datevalues[1] . "/" . $datevalues[0] . " " . $timevalues[0] . ":" . $timevalues[1] . ":" . $timevalues[2];
        } else {
          $string = $datevalues[0] . "/" . $datevalues[1] . "/" . (isset($datevalues[2]) ? $datevalues[2] : date("Y")) . " " . $timevalues[0] . ":" . $timevalues[1] . ":" . $timevalues[2];
        }
      } else {
        $datevalues = explode("-", $string);
        if ((integer)$datevalues[0] <= 1980) {
          $string = (isset($datevalues[2]) ? $datevalues[2] : date("Y")) . "/" . $datevalues[1] . "/" . $datevalues[0];
        } else {
          $string = $datevalues[0] . "/" . $datevalues[1] . "/" . (isset($datevalues[2]) ? $datevalues[2] : date("Y"));
        }
      }
    }

    return $string;
  }

  function mysql_date2($string) {
    if (comparetext($string, ["{submit:datetime}", "[submit:datetime]"])) {
      $string = date("Y/m/d H:i:s");
    } elseif (comparetext($string, ["{submit:datetimestring}", "[submit:datetimestring]"])) {
      $string = date("YmdHis");
    } elseif (comparetext($string, ["{submit:date}", "[submit:date]"])) {
      $string = date("Y/m/d");
    } elseif (comparetext($string, ["{submit:datestring}", "[submit:datestring]"])) {
      $string = date("Ymd");
    } elseif (comparetext($string, ["{submit:time}", "[submit:time]"])) {
      $string = date("H:i:s");
    } elseif (comparetext($string, ["{submit:timestring}", "[submit:timestring]"])) {
      $string = date("His");
    } elseif (stripos($string, "GMT") !== false) {
      $datevalues = explode(" ", $string);
      $string = $datevalues[2] . "-" . maandnummerstr($datevalues[1]) . "-" . $datevalues[3];
    } else if (stripos($string, "UTC") !== false) {
      $datevalues = explode(" ", $string);
      $string = $datevalues[2] . "-" . maandnummerstr($datevalues[1]) . "-" . $datevalues[5];
    } elseif (stripos($string, "/") !== false) {
      $dateparts  = explode(" ", $string);
      $datevalues = explode("/", $dateparts[0]);
      $string = $datevalues[2] . "-" . $datevalues[1] . "-" . $datevalues[0];
    }

    return $string;
  }


  function is_date( $str ) {
    $stamp = strtotime( $str );

    if (!is_numeric($str)) {
       return FALSE;
    }
    $month = date( 'm', $stamp );
    $day   = date( 'd', $stamp );
    $year  = date( 'Y', $stamp );

    if (checkdate($month, $day, $year)) {
       return TRUE;
    }

    return FALSE;
  }

  function europeandate ($datestr) {
    if (is_date($datestr)) {
      return date( 'm-d-Y', strtotime( $datestr) );
    } else {
      return $datestr;
    }
  }

?>