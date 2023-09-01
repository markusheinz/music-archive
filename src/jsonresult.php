<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

class JsonResult {
  public $result; // has to be public, otherwise json_encode does not work
  public $count; // same as above

  public function __construct($result, $count) {
    $this->result = $result;
    $this->count = $count;
  }

  public function toJson() {
    return json_encode($this);
  }
};
?>
