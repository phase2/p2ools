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
    if ($this->offsetExists($k)) {
      return parent::offsetGet($k);
    }
    else {
      return NULL;
    }
  }
}

