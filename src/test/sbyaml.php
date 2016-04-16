<?php
  //use Manipulator;

  use Illuminate\Support\Facades\Session;
  use SB\Functions as sb;
  use SB\Facades\YAMLParser;
  use App\Facades\Module;
  use App\Facades\Optie;
  use App\Facades\Label;

  //gebruik output buffer zodat alle sb\log's goed gaan
  ob_start();

//$a=YAMLParser::getFacadeRoot();
  //print_r($a::$Filename);


  $timer = sb\timer_start();

  $taalid="nl";
  $orgname="Debuut";

  $modulefilenames = [
      base_path() . "/usersettings/_default/settings/modules.blade.yaml"
    , base_path() . "/usersettings/" . $orgname . "/settings/modules.blade.yaml"
  ];
  Module::filename($modulefilenames);
  Module::load();

  $labelfilenames = [
      //base_path() . "/usersettings/default/lang/" . $taalid . "/labels.blade.yaml"
      base_path() . "/usersettings/_default/lang/" . $taalid . "/labels.blade.yaml"
      , base_path() . "/usersettings/" . $orgname . "/lang/" . $taalid . "/labels.blade.yaml"
  ];
  Label::filename($labelfilenames);
  Label::load();

  //opties
  $optiefilenames = [
      base_path() . "/usersettings/_default/settings/opties.blade.yaml"
    , base_path() . "/usersettings/" . $orgname . "/settings/opties.blade.yaml"
  ];
  Optie::filename($optiefilenames);
  Optie::load();

  sb\pb("-files-------");
  sb\pb(Label::filename());
  sb\pb("-------------");

  //Session::put("Temp.RegioID", "56052");
  $test = sb\pb(Optie::get("autorisator.email", true));


  sb\pb(Label::get("klant:lower"));
  sb\pb(Label::get("categorie:plural"));
  sb\pb(Label::get("velden.categorie.naam:plural:upper"));

  sb\pb("-------------");

  //$a= Label::get('shared.calendar'));

  //YAMLParser::$Filename = base_path() . "/usersettings/default/settings/opties.yaml";
  //$test   = YAMLParser::get("autorisator");
  //$test  = YAMLParser::get("modules.servicemeldingen.listfields.klant.iconclass");
  //$test  = YAMLParser::get("modules.configuratie.enabled");
  //$test  = YAMLParser::get("modules");
  //$test  = YAMLParser::get();
  //$test  = sb\booltostr(Module::get("documenten"));
  //$test  = YAMLParser::get();
  //$test  = YAMLParser::getObject("personeel");
  //$test  = YAMLParser::getArray("autorisator.naam");

  //$test  = Optie::get("modules.documenten");
  //sb\pb($test);

  $test  = Optie::get("modules.servicemeldingen.naam");
  sb\pb($test);

  $test  = sb\booltostr(Optie::get("modules.servicemeldingen.visible"));
  sb\pb($test);

  $test  = sb\booltostr(Optie::get("modules.documenten.visible"));
  sb\pb($test);

  $test  = Optie::getString("emailadressen.behandelaar.test");
  sb\pb($test);

  //$test  = Optie::get("modules");
  //sb\pb($test);

  $test  = Optie::getString("beheerder.email", true);
  sb\pb($test);

  $test  = Optie::get("modules.servicemeldingen.actiemails.TOEVOEGEN");
  sb\pb($test);


  sb\pb(Label::get("acties.behandelen"));
  sb\pb(Label::get("acties.behandelen.zelfstandignaamwoord"));
  sb\pb(Label::singular("acties.behandelen.zelfstandignaamwoord"));
  sb\pb(Label::plural("acties.behandelen.zelfstandignaamwoord"));

  sb\pb(Label::get("projectstatustype"));
  sb\pb(Label::singular("projectstatustype"));
  sb\pb(Label::plural("projectstatustype"));

  sb\pb(Label::get("categorie"));
  sb\pb(Label::singular("velden.categorie.naam"));
  sb\pb(Label::get("velden.categorie.required"));
  sb\pb(Label::get("velden.categorie.placeholder"));

  sb\pb(Label::get("acties.vervallen.boodschap"));

  $labels = Label::getAll();
  sb\log($labels); //werkt alleen als ob_start() bovenaan script staat

  sb\pb(sb\timer_end_duration($timer,'---------------------',true));

