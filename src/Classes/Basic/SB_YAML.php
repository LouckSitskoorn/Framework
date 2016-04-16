<?
  //NAMESPACE
  namespace SB\Classes\Basic;

  //USES
  use Symfony\Component\Yaml\Parser;
  use SB\Functions as sb;

  //CLASSES
  class SB_YAML {
    public static function get($yamlfilename=NULL, $yamlstring=NULL) {
      if ($yamlfilename
      &&  $yamlstring) {
        $yamlparser = new Parser();

        $filename     = base_path() . "/" . sb\stripfirstslash($yamlfilename);
        $filecontents = file_get_contents($filename);

        $yaml = $yamlparser->parse($filecontents);



      }
    }
  }
