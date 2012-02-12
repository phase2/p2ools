# p2ools

A collection of utilities that make writing Drupal and PHP easier. This should
serve as a point of consolidation for utilities that are written and re-written
across Drupal projects.

P2ools currently includes

* a logging abstraction

```php
<?php
$lg = new Logger("foo_module");
$lg->info("This is a var!", array(1, 2, 3));

  => "[file basename]:[line] This is a var! Array
      (
          [0] => 1
          [1] => 2
          [2] => 3
      )"
```

* a fancier taxonomy API

```php
<?php
$t = new Tax();
$t->get_term("saucy", "tags");

  => stdClass rep. of "saucy" term in "tags" vocab
```

* an array wraper

```php
<?php
$a = array(1, 2, 20, 25);
$arr = new ArrWrap($a);
$arr
  ->filter(function ($v) {return $v > 15;})
  ->map(function($v) {return $v * 2;});

  => <object #380 of type ArrWrap> {
      arr => array(
        2 => 40,
        3 => 50,
      ),
    }
```

## Using it

Simply include the following in your Drush makefile:

    projects[p2ools][type] = module
    projects[p2ools][subdir] = contrib
    projects[p2ools][download][type] = git
    projects[p2ools][download][url] = git://github.com/phase2/p2ools.git 

Afterwards, all of the classes contained in p2ools will be at your disposal.
 
## Testing it

Tests can be run using the following command:

    drush test-run --uri=[url of test site] p2ools

## Guidelines for contribution
                          
* Pull requests should be submitted for contributions.
* All contributed code must have corresponding unit-tests written and placed
  in `tests/`. The goal here is to have 100% test coverage of p2ools.
* Contributed code must follow the Drupal style guide.

## API

### Logger

A logging abstraction that wraps Drupal's watchdog. Keeps track of your module
name and features logging levels.

#### Usage

    $log = new Logger('modulename', Logger::INFO);
    $log->info('I need to test the watchdog log!');
    $log->error('Unexpected result', __FILE__, __LINE__);
    $log->warn('I warned you!', __FILE__);

### Tax

Taxonomy manipulation done easier.

#### Summary

* `Tax::get_term(name_or_tid [,vocab_name_or_vid])`: An easy way to retrieve 
  a taxonomy object.
* `Tax::get_tid(name_or_tid [,vocab_name_or_vid])`: An easy way to retrieve a
  tid.

#### Usage

##### Fancy taxonomy indexing

    php> $tax_util = new Tax();

    php> = $tax_util->get_term('foobar');
    false
    
    php> $tax_term = new stdClass();
    php> $tax_term->name = "foobar";
    php> $tax_term->vid = 1;
    php> = taxonomy_term_save($tax_term);
    1

    php> $the_term = $tax_util->get_term('foobar');
    php> = $the_term
    <object #92 of type stdClass> {
      tid => "1",
      vid => "1",
      name => "foobar",
      description => null,
      format => null,
      weight => "0",
      vocabulary_machine_name => "tags",
      rdf_mapping => array(
        "rdftype" => array(
          0 => "skos:Concept",
        ),
        "name" => array(
          "predicates" => array(
            0 => "rdfs:label",
            1 => "skos:prefLabel",
          ),
        ),
        "description" => array(
          "predicates" => array(
            0 => "skos:definition",
          ),
        ),
        "vid" => array(
          "predicates" => array(
            0 => "skos:inScheme",
          ),
          "type" => "rel",
        ),
        "parent" => array(
          "predicates" => array(
            0 => "skos:broader",
          ),
          "type" => "rel",
        ),
      ),
    }        
    php> assert ($the_term == $tax_util->get_term('foobar', 1))
    php> assert ($the_term == $tax_util->get_term('foobar', 'tags'))
    php> assert ($the_term == $tax_util->get_term(1, 1))
    php> assert ($the_term == $tax_util->get_term(1, 'tags'))

##### Easy tid retrieval

    php> // use with the same args above
    php> = $tax_util->get_tid(1)
    "1"
    php> = $tax_util->get_tid('foobar')
    "1"
    php> = $tax_util->get_tid('foobar', 'tags')
    "1"

### ArrWrap

#### Real quick

Check out this PHP:

    $x = 1;
    $nums = array(10, 20, 30, 40);

    $arr = array();
    foreach ($nums as $n)
      if ($n > 15)
        $arr[] = $n * 2 + $x;
    $res = 0;
    foreach ($arr as $r)
      $res -= $r;

Gross, right? Now check out this PHP:
      
    $aw_nums = new ArrWrap($nums);
    $res = $aw_nums
      ->filter(function($v) {return $v > 15;})
      ->map(function($v) use ($x) {return $v * 2 + $x;})
      ->reduce(function($v, $w) {return $v + $w;});

Now we're talkin'.

When you're writing PHP, you're using arrays. A lot. Unfortunately, arrays
in PHP are pretty cumbersome.

Wouldn't you like an array that

* returns null if the key you've referenced doesn't exist?
* allows you to use it like an object, including all array functions as methods?
* allows method chaining?
* is subclass-able?
* includes nice utility methods?

Wait no longer. 

`ArrWrap` is an Array-like object that makes using arrays easier at almost no 
performance cost, since it just wraps array references. Check out a sample 
usage.
    
#### Usage

    php> $a = new ArrWrap();
    php> $a[1] = 2;
    php> = $a[1];
    2
    php> = $a['abc'];
    php> if (!$a['abc']) echo "foobar";
    foobar

Tired of repeating `isset($a[$foo]) ? $a[$foo] : 'abc';`? Skip that noise
and use `ArrWrap::val_or`:

    php> echo $a->val_or('abc', 1);
    1
    php> $a['yo'] = 'bar';
    php> echo $a->val_or('yo', 1);
    bar

How about more concise access to your favorite array functions?

    php> $arr = new ArrWrap(array_fill(0, 3, 1));

    php> = $arr->unique();
    <object #607 of type ArrWrap> {
      arr => array(
        0 => 1,
      ),
    }

    php> $u = $arr->unique();
    php> = $u[0];
    1

    php> = $arr->merge(array(1, 2, 3));
    <object #607 of type ArrWrap> {
      arr => array(
        0 => 1,
        1 => 1,
        2 => 1,
        3 => 1,
        4 => 2,
        5 => 3,
      ),
    }

    php> = $arr->chunk(2);
    <object #607 of type ArrWrap> {
      arr => array(
        0 => array(
          0 => 1,
          1 => 1,
        ),
        1 => array(
          0 => 1,
        ),
      ),
    }

That's right: any `array_` function that accepts an array as the first
argument is automagically a method of `ArrWrap` objects.

But what about functions that aren't? Easy. The `arr` attribute of any
`ArrWrap` object contains its underlying array.

    php> = count($arr->arr);
    3

`ArrWrap` objects are also fully iterable.

    php> foreach($arr as $num) {
     ...   print_r($num);
     ... }
    111

    php> foreach($arr as $i => $num) {
     ...   print_r("${i} => ${num}; ");
     ... }
    0 => 1; 1 => 1; 2 => 1;



