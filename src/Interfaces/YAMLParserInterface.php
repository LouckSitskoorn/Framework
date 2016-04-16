<?php
  //NAMESPACE
  namespace SB\Interfaces;

  //USES

  //INTERFACES
  interface YAMLParserInterface {
    /**
     * get an item from a yaml file
     *
     * @param  string $yamlstring
     * @return mixed
     */
    public function get($yamlstring="");
  }
