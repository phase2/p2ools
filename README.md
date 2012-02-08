# p2ools

A collection of utilities that make writing Drupal and PHP easier. This should
serve as a point of consolidation for utilities that are written and re-written
across Drupal projects.

P2ools currently includes

* an array wrapper,
* a fancier taxonomy API, and
* a logging abstraction.

## Guidelines for contribution

* All contributed code must have corresponding unit-tests written and placed
  in `tests/`. The goal here is to have 100% test coverage of p2ools.
* Contributed code must follow the Drupal style guide.

## Running the tests

Tests can be run using the following command:

    drush test-run --uri=[url of test site] [classname of test case]

## API

### ArrWrap

An Array-like object that returns `NULL` when a key that does not exist is
referenced.

#### Usage

    php > $a = new ArrWrap();
    php > $a[1] = 2;
    php > echo $a[1];
    2
    php > echo $a['abc'];
    php > if ($a['abc'] === NULL) echo "foobar";
    foobar

Tired of repeating `return isset($a[$foo]) ? $a[$foo] : 'abc';`? Skip that noise
and use `ArrWrap::val_or`:

    php > echo $a->val_or('abc', 1);
    1
    php > $a['yo'] = 'bar';
    php > echo $a->val_or('yo', 1);
    bar

### Logger

A logging abstraction that wraps Drupal's watchdog.

#### Usage

    $log = new Logger('modulename', Logger::INFO);
    $log->info('I need to test the watchdog log!');
    $log->error('Unexpected result', __FILE__, __LINE__);
    $log->warn('I warned you!', __FILE__);


