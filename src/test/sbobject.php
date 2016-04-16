<?php
  use SB\Classes\Basic\SB_Object;

  $obj=new SB_Object();

  $obj->setProperties(["TestString"=>"noot"]);

  echo $obj->TestString;

  function manyVars(...$params) {
    var_dump($params);
  }

  manyVars("aap","noot", true);