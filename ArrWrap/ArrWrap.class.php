<?php

class ArrWrap implements ArrayAccess, IteratorAggregate {
  /**
   * A reference to the underlying array we're wrapping.
   */
  public $arr;

  /**
   * @param &$existing_arr A reference to the array we want to wrap.
   */
  function __construct(&$existing_arr=null) {
    $this->arr = (is_null($existing_arr)) ? array() : $existing_arr;
  }

  /**
   * This is a hack to allow users to call "array_" functions as methods,
   * automagically passing the underlying array as the first argument.
   */
  public function __call($func, $args) {
    $ret_val = null;

    if (function_exists('array_' . $func)) {
      $ret_val = $this->callArrayFunc($func, $args);
    }
    else if (!$args) {
      $ret_val =& $this->arr;
    }
    else {
      $ret_val = parent::__call($func, $arguments);
    }

    return $this->wrap($ret_val);
  }

  /**
   * Make a call to an array_ function.
   *
   * @param $func The function
   * @param $args An array of args passed to the function
   * @param $array_pos The position of the array being operated on in the 
   *   function's arguments.
   */
  private function callArrayFunc($func, $args, $array_pos=0) {
    $args = array_merge(array_slice($args, 0, $array_pos),
                        array($this->arr),
                        array_slice($args, $array_pos));

    return call_user_func_array('array_' . $func, $args);
  }

  /**
   * Magic method for ArrayAccess.
   */
  public function offsetSet($offset, $value) {
    $value = $this->wrap($value);

    if (is_null($offset)) {
      $this->arr[] = $value;
    }
    else {
      $this->arr[$offset] = $value;
    }
  }

  /**
   * Magic method for ArrayAccess.
   */
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->arr);
  }

  /**
   * Magic method for ArrayAccess.
   */
  public function offsetUnset($offset) {
    unset($this->arr[$offset]);
  }

  /**
   * Override of offsetGet.
   *
   * @parm $k The key for the desired value.
   * @return The value for key $k, if one exists; if not, return NULL.
   */
  public function offsetGet($k) {
    return $this->wrap($this->get($k));
  }

  /**
   * For IteratorAggregate.
   */
  public function getIterator() {
    return new ArrayIterator($this->arr);
  }

  /**
   * Return the value at an index or, if that index doesn't exist, return a 
   * default.
   *
   * @param $k The key of the value to return.
   * @param $default The default to return if the key doesn't exist
   * @return The value at $k or $default if $k doesn't exist.
   */
  public function get($k, $default=NULL) {
    if (array_key_exists($k, $this->arr)) {
      return $this->arr[$k];
    }
    else {
      return $default;
    }
  }

  /**
   * A count wrapper.
   */
  public function count() {
    return count($this->arr);
  }

  /**
   * A wrapper for array_map.
   *
   * @param $callback The callback to apply to this object's array.
   */
  public function map($callback) {
    $ret_val = $this->callArrayFunc('map', array($callback), 1);
    return $this->wrap($ret_val);
  }

  /**
   * Utility method for converting a return value into an ArrWrap object if
   * it is an array.
   */
  private function wrap($value) {
    return (is_array($value)) ? new ArrWrap($value) : $value;
  }
}

