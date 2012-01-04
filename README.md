# p2ools

A collection of utilities that make writing PHP easier.

## ArrWrap

An Array-like object that returns `NULL` when a key that does not exist is
referenced.

### Usage

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



