# p2ools

A collection of utilities that make writing Drupal and PHP easier. This should
serve as a point of consolidation for utilities that are written and re-written
across Drupal projects.

P2ools currently includes

* an array wrapper,
* a fancier taxonomy API, and
* a logging abstraction.

## Using it

Simply include the following in your Drush makefile:

    projects[p2ools][type] = module
    projects[p2ools][subdir] = contrib
    projects[p2ools][download][type] = git
    projects[p2ools][download][url] = git://github.com/phase2/p2ools.git 

Afterwards, all of the classes contained in p2ools will be at your disposal.

## Guidelines for contribution
                          
* All contributed code must have corresponding unit-tests written and placed
  in `tests/`. The goal here is to have 100% test coverage of p2ools.
* Contributed code must follow the Drupal style guide.

## Running the tests

Tests can be run using the following command:

    drush test-run --uri=[url of test site] [classname of test case]

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

#### Usage

    

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


