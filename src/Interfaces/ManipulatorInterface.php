<?php
  //NAMESPACE
  namespace SB\Interfaces;

  //USES
  use Closure;

  //INTERFACES
  interface ManipulatorInterface {
    /**
     * Manipulate a (sql) result
     *
     * @param  array $result
     * @param  array $propertyarray
     * @return array
     */
    public function manipulate($result, $propertyarray=[]);
  }
