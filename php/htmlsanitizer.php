<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015. 2022 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

/**
 * This class provides methods for stripping HTML tags from strings contained 
 * in objects or arrays of objects. Multi dimensional arrays and nested objects
 * are supported too.
 */
class HtmlSanitizer {

  /**
   * This method strips HTML tags from all strings contained in the given
   * object or array of objects. The objects are modified in place.
   *
   * @param object an object or array of objects to process
   */
  public static function sanitize($object) {
    if (is_array($object)) {

      foreach ($object as $element) {
        self::sanitize($element);
      }

    } else if (is_object($object)) {

      self::internalSanitize($object);

    }
  }

  /**
    * This method removes all HTML tags from the strings in the given object.
    * If an array or a nested object is encountered as member of the current 
    * object a recursive invocation is performed. The object is modified in 
    * place. Reflection is used to identify the properties of the object.
    *
    * @param object the object to process
    */
  private static function internalSanitize($object) {
    $reflectionObject = new ReflectionObject($object);
    $properties = 
      $reflectionObject->getProperties(ReflectionProperty::IS_PUBLIC);

    foreach ($properties as $property) {
      $value = $property->getValue($object);

      if (is_array($value) || is_object($value)) {

        self::sanitize($value);

      } else if (is_string($value)) {

        $newvalue = htmlspecialchars($value, ENT_COMPAT);
        $property->setAccessible(true);
        $property->setValue($object, $newvalue);

      }
    }
  }
}

?>
