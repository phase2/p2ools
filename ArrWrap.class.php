<?php

class ArrWrap extends ArrayObject 
{

  /**
   * Override of offsetGet.
   *
   * @parm $k The key for the desired value.
   * @return The value for key $k, if one exists; if not, return NULL.
   */
  public function offsetGet($k) {
    return $this->val_or($k);
  }

  /**
   * Return the value at an index or, if that index doesn't exist, return a 
   * default.
   *
   * @param $k The key of the value to return.
   * @param $default The default to return if the key doesn't exist
   * @return The value at $k or $default if $k doesn't exist.
   */
  public function val_or($k, $default=NULL) {
    if ($this->offsetExists($k)) {
      return parent::offsetGet($k);
    }
    else {
      return $default;
    }
  }
}

