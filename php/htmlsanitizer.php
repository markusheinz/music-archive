<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

class HtmlSanitizer {

  public static function sanitize($object) {
    if (is_array($object)) {

      foreach ($object as $element) {
        self::sanitize($element);
      }

    } else if (is_object($object)) {

      self::internalSanitize($object);

    }
  }

  private static function internalSanitize($object) {
    $reflectionObject = new ReflectionObject($object);
    $properties = 
      $reflectionObject->getProperties(ReflectionProperty::IS_PUBLIC);

    foreach ($properties as $property) {
      $value = $property->getValue($object);

      if (is_array($value) || is_object($value)) {

        self::sanitize($value);

      } else if (is_string($value)) {

        $newvalue = htmlspecialchars($value);
        $property->setAccessible(true);
        $property->setValue($object, $newvalue);

      }
    }
  }
}

?>
