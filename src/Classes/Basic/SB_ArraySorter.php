<?php
  //NAMESPACE
  namespace SB\Classes\Basic;

  //INCLUDES  functions
  include_once __DIR__ . "/../Functions/array_functions.php";

  //CLASSES
  class SB_ArraySorter {
    var $details = array();
    var $sortFields = array();
    var $sortDirections = array();

    function _objSort(&$a, &$b, $i = 0) {
      $field        = $this->sortFields[$i];
      $direction    = $this->sortDirections[$i];

      $diff = strnatcmp($this->details[$a]->$field, $this->details[$b]->$field) * $direction;
      if ($diff == 0 && isset($this->sortFields[++$i])) {
        $diff = $this->_objSort($a, $b, $i);
      }
      return $diff;
    }

    function ArraySorter(&$array, $sortFields) {
      $i = 0;
      if ($array) {
        foreach ($sortFields as $field => $direction) {
          $this->sortFields[$i] = $field;
          $direction == "DESC" ? $this->sortDirections[$i] = -1 : $this->sortDirections[$i] = 1;
          $i++;
        }

        $this->details = $array;//$this->Fields;
        uksort($this->details, array($this, "_objSort"));
        $array = $this->details;

        $this->sortFields = array();
        $this->sortDirections = array();
      }
    }
  }
?>