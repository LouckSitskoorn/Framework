<?php
  //NAMESPACE
  namespace SB\Classes\Database;

  //USES
  use SB\Classes\Basic\SB_Object;
  use SB\Functions as sb;

  //CLASSES
  class SB_Field
  extends SB_Object {
    //published properties
    public $Fieldname;
    public $FieldnameAlias;
    public $LookupTablename;
    public $LookupTableAbbrevation;
    public $LookupFieldname;
    public $LookupCondition;
    public $Tablename;
    public $TableAbbrevation;
  }