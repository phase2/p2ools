<?php

/**
 * A file demonstrating the use of ArrWrap in comparison to standard PHP arrays.
 */


/**
 * Filtered map.
 * 
 * For fun, here's a Groovy implementation:
 * 
 *   def x = 1
 *   def res = nums.findAll { it > 15 } .collect { it * 2 + x} .sum()
 */

function filteredMapReg($arr) {
  $x = 1;

  // Notice this must be backwards because we are applying functions,
  // not chaining methods.
  //
  // It's also not clear what we're operating on ($arr).
  return array_reduce(
      array_map(function($v) use ($x) { return $v * 2 + $x; },
                array_filter($arr, function($v) { return $v > 15; })),
      function($v, $w) {return $v + $w;}
  );
}

function filteredMap($arr) {
  $x = 1;

  // Much cleaner.
  return $arr
    ->filter(function($v) {return $v > 15;})
    ->map(function($v) use ($x) {return $v * 2 + $x;})
    ->reduce(function($v, $w) {return $v + $w;});
}
 
$nums = array(10, 20, 30, 40);
$aw_nums = new ArrWrap($nums);
                              
assert (filteredMapReg($nums) == filteredMap($aw_nums));
                                                                     

/**
 * Form checking.
 * 
 * Okay, so you don't buy that filter/map/reduce patterns are relevant in
 * Drupal. I would disagree, but let's look at the common example of checking
 * form values.
 */
