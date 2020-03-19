<?php

namespace App\Facades;

use RuntimeException;

abstract class Facade {

  protected static function resolveFacade(){
    throw new RuntimeException('A facade has not been resolved.');
  }

  public static function __callStatic($method, $arguments){

    $instance = static::resolveFacade();

    $class_methods = static::getClassMethods($instance);

    if(static::hasMethod($class_methods, $method)){
      return $instance->$method(...$arguments);
    }    
    static::badMethod();
    return;
  }

  private static function hasMethod($class_methods, $method){
    return in_array($method, $class_methods);
  }

  private static function getClassMethods($instance){
    return get_class_methods(get_class($instance));
  }

  private static function badMethod(){
    throw new RuntimeException('Right method is not called.');
  }
  

}