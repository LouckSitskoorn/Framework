<?
  //NAMESPACE
  namespace SB\Functions;

  //FUNCTIONS
  function fputcsv_null($fh, array $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) {
    //$delimiter_esc = preg_quote($delimiter, '/');
    //$enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ($fields as $field) {
      if ($field === null && $mysql_null) {
        $output[] = 'NULL';
        continue;
      }

      //$output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? (
      //    $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure
      //) : $field;
      if (is_string($field)) {
        $output[] = $enclosure.$field.$enclosure;
      } else {
        $output[] = $field;
      }

    }

    fwrite($fh, join($delimiter, $output) . "\n");
  }

?>