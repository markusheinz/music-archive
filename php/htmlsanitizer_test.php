<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

require_once "htmlsanitizer.php";

class TestObject {
  public $value1;
  public $value2;

  public function __construct($value1, $value2) {
    $this->value1 = $value1;
    $this->value2 = $value2;
  }
}

class HtmlSanitizerTest extends PHPUnit_Framework_TestCase {
  
  private $objectA;
  private $objectB;
  private $objectArray;
  private $nestedObject;

  const TEST_STRING_A = '<b>bold</b>';
  const TEST_STRING_B = '<script>alert(\'test\');</script>';

  const RESULT_STRING_A = '&lt;b&gt;bold&lt;/b&gt;';
  const RESULT_STRING_B = '&lt;script&gt;alert(\'test\');&lt;/script&gt;';
   

  public function setUp() {
    $this->objectA = new TestObject(self::TEST_STRING_A, self::TEST_STRING_B);
    $this->objectB = new TestObject(self::TEST_STRING_B, self::TEST_STRING_A);

    $this->objectArray = array($this->objectA, $this->objectB);

    $this->nestedObject = new TestObject(self::TEST_STRING_A, 
                                         $this->objectArray);
   }

  public function testObject() {
    HtmlSanitizer::sanitize($this->objectA);
    $sanitizedObject = $this->objectA;

    $this->assertEquals (self::RESULT_STRING_A, $sanitizedObject->value1);
    $this->assertEquals (self::RESULT_STRING_B, $sanitizedObject->value2);

    HtmlSanitizer::sanitize($this->objectB);
    $sanitizedObject = $this->objectB;

    $this->assertEquals (self::RESULT_STRING_B, $sanitizedObject->value1);
    $this->assertEquals (self::RESULT_STRING_A, $sanitizedObject->value2);
  }

  public function testArray() {
    HtmlSanitizer::sanitize($this->objectArray);
    $sanitizedArray = $this->objectArray;

    $this->assertEquals (self::RESULT_STRING_A, $sanitizedArray[0]->value1);
    $this->assertEquals (self::RESULT_STRING_B, $sanitizedArray[0]->value2);
    $this->assertEquals (self::RESULT_STRING_B, $sanitizedArray[1]->value1);
    $this->assertEquals (self::RESULT_STRING_A, $sanitizedArray[1]->value2);
  }

  public function testNestedObject() {
    HtmlSanitizer::sanitize($this->nestedObject);
    $sanitizedObject = $this->nestedObject;

    $this->assertEquals (self::RESULT_STRING_A, $sanitizedObject->value1);
    $this->assertEquals (self::RESULT_STRING_A, 
                         $sanitizedObject->value2[0]->value1);
    $this->assertEquals (self::RESULT_STRING_B, 
                         $sanitizedObject->value2[0]->value2);
    $this->assertEquals (self::RESULT_STRING_B, 
                         $sanitizedObject->value2[1]->value1);
    $this->assertEquals (self::RESULT_STRING_A, 
                         $sanitizedObject->value2[1]->value2);
  }
}

?>
