<?
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDES functions
  include_once __DIR__ . "/string_functions.php";

  //FUNCTIONS
  function is_odd($number) {
    return($number & 1);
  }

  function is_even($number) {
    return(!($number & 1));
  }

  function get_percentage($total, $percentage) {

    if (right(trim($percentage),1)=='%') {
      $returnvalue = strval($total) * (strval($percentage) / 100);
    } else {
      $returnvalue = $percentage;
    }

    return $returnvalue;
  }

  function maximize($number, $maximum) {
    if ($number>$maximum) {
      return $maximum;
    } else {
      return $number;
    }
  }

  function minimize($number, $minimum) {
    if ($number<$minimum) {
      return $minimum;
    } else {
      return $number;
    }
  }
?>