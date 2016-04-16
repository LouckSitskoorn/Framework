<?php
  //use Manipulator;
  use SB\Services\ManipulatorService;
  use SB\Classes\Basic\SB_Object;
  use SB\Classes\Database\SB_Field;
  use SB\Functions as sb;
  use SB\Facades\Manipulator;
  use SB\Facades\LokatieManipulator;

  //$obj=new SB_Object(["TestString"=>"organisatie,lokatietype"]);
  //echo $obj->TestString;

  //$ms=new ManipulatorService(["TypesIncluded"=>"organisatie,lokatietype"]);
  //$ms = App::make("ManipulatorService");

  $r1 = [
      ["LokatieIDs"=>"155,156,158,159;{OrganisatieID in (4,173) AND LokatierolID='MELDER'};[organisatie];[testlokaties]"]
    , ["LokatieIDs"=>"123"]
  ];

  $r5 = [
      ["LokatieIDs"=>"[klant];155;{LokatieID=158}"]
  ];

  /*
  $m2 = $ms->manipulate($r1, [
      "Tablename"=>"lokaties"
    , "IDFieldname"=>"LokatieID"
    , "IDsFieldname"=>"LokatieIDs"
    , "Fields"=>"Naam,Adres,OrganisatieID,LokatierolID"
    , "Lookups"=>[new SB_Field(["Tablename"=>"organisaties","Fieldname"=>"Naam","FieldnameAlias"=>"OrganisatieNaam","LookupTablename"=>"organisaties","LookupFieldname"=>"OrganisatieID"])]
    , "TypeAliases"=>["organisatie"=>"OrganisatieID"]
  ]);
  */

  /*
  $m3=ManipulatorService::manipulate($r1, [
      "Tablename"=>"lokaties"
    , "IDFieldname"=>"LokatieID"
    , "IDsFieldname"=>"LokatieIDs"
    , "Fields"=>"Naam,Adres,OrganisatieID,LokatierolID"
    , "Lookups"=>[new SB_Field(["Tablename"=>"organisaties","Fieldname"=>"Naam","FieldnameAlias"=>"OrganisatieNaam","LookupTablename"=>"organisaties","LookupFieldname"=>"OrganisatieID"])]
    , "TypeAliases"=>["organisatie"=>"OrganisatieID"]
  ]);
  sb\pb($m3);
  */

  /*
  $m4 = Manipulator::manipulate($r1, [
      "Tablename"=>"lokaties"
    , "IDFieldname"=>"LokatieID"
    , "IDsFieldname"=>"LokatieIDs"
    , "Fields"=>"Naam,Adres,OrganisatieID,LokatierolID"
    , "Lookups"=>[new SB_Field(["Tablename"=>"organisaties","Fieldname"=>"Naam","FieldnameAlias"=>"OrganisatieNaam","LookupTablename"=>"organisaties","LookupFieldname"=>"OrganisatieID"])]
    , "TypeAliases"=>["organisatie"=>"OrganisatieID"]
  ]);
  sb\pb($m4);
  */


  $m5 = LokatieManipulator::manipulate($r5, [
      "OrganisatieID"=>20050
    , "KlantID"=>20347
    , "Fields"=>"LokatieID"
    , "Lookups"=>[]
  ]);
  sb\pb($m5);

  //function manyVars(...$params) {
  //  var_dump($params);
  //}

  //manyVars("aap","noot", true);