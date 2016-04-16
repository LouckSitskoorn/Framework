<?php
  namespace SB\Facades;

  use Illuminate\Support\Facades\Facade;

  class YAMLParser
  extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
      return 'yamlparser';
    }
  }
