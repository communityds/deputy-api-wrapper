# Querying resources

The wrapper allows lists of resources to be filtered using a query object that abstracts the `resource/:Name/QUERY` endpoint.
To create a query use the `find<Name>` helper function on the wrapper instance.

## Retrieving results

Use the `one()` function to return the first entry or use the `all()` function to return all records.

```php
$query = $wrapper->findEmployees();
$first = $query->one();   // return first employee
$all = $query->all();     // return all employees 
```

## Conditions

Conditions are added using the `where()` or `andWhere()` functions on the query instance.
Due to Deputy API limitations all where conditions are joined via an `AND` condition.
Conditions should be defined using one of the following approaches:

```php
$activeEmployees = $wrapper->findEmployees()->andWhere(['active' => true])->all();   // find all active employees
```

### Hashed Conditions

Conditions checking for equal matches can be added as a hashed condition where the key is the field name and the value is the expected value or values:

```php
[
	'id' => [1, 2, 3],
	'active' => true
]
```

Hashed Conditions are essentially converted to Operator Conditions. For example the above is converted to:

```php
[
	['in', 'id', [1, 2, 3]],
	['eq', 'active', true],
]
```

### Operator Conditions

Conditions that use a specific operator to compare against a value. Typically in the format of: `[$operator, $field, $value]`.

* `=` or `eq`: field must equal a scalar value. For example: `['=', 'active', true]`
* `!=` or `ne`: field must not equal a scalar value. For example: `['!=', 'active', true]`
* `>` or `gt`: field must be greater than a scalar value. For example: `['>', 'costTotal', 100]`
* `>=` or `ge`: field must be greater than or equal to a scalar value. For example: `['>=', 'costTotal', 100]`
* `<` or `lt`: field must be less than a scalar value. For example: `['<', 'costTotal', 100]`
* `<=` or `le`: field must be less than or equal to a scalar value. For example: `['<=', 'costTotal', 100]`
* `like` or `lk`: field must be like a string value where `%` represents one or more characters. For example: `['like', 'displayName', '%doe%']`
* `not like` or `nk`: field must not be like a string value where `%` represents one or more characters. For example: `['not like', 'displayName', '%doe%']`
* `in`: field must equal one of the scalar values in an array. For example: `['in', 'status', [1, 4, 5]]`
* `not in` or `nn`: field must not equal one of the scalar values in an array. For example: `['not in', 'status', [2, 3]]`
* `not empty` or `is`: field must not be `0` or `null`. For example: `['not empty', 'address']`
* `empty` or `ns`: field must be `0` or `null`. For example: `['empty', 'address']`

## Ordering Results

Use the `orderBy()` function to set the sorting conditions.

```php
$results = $wrapper->findEmployees()
    ->orderBy(
        [
            'startDate' => SORT_DESC,
            'lastName' => SORT_ASC,
            'firstName' => SORT_ASC,
        ]
    )->all();
```

The above example returns employees alphabetically by last name from latest to oldest start date.

## Limits and Offsets

Use the `limit()` function to set the maximum number of records per line.

```php
$employees = $wrapper->findEmployees()->limit(10)->all();   // return ten employees
```

Use the `offset()` function to set the starting position within the result set.

```php
$employees = $wrapper->findEmployees()->offset(20)->all();   // return all employees starting at the 20th
```

Use both to add pagination or batch processing:

```php
$employees = $wrapper->findEmployees()->limit(10)->offset(10)->all();  // returns page '2' of results
```

## Determining number of results

Due to API limitations it is not possible to determine the number of results for a potentially filtered list of records.
So at this stage the only approach is to return the entire result set.
This is not ideal as it requires the complete data set to be loaded.

```php
$count = count($wrapper->findEmployees()->where(['active' => true])->all());
```

## Eager-Loading

The Deputy API allows eager-loading of a single tier from a resource.
To indicate what sub-objects should be eagerly loaded call the `joinWith()` function on the query object.

```php
$employees = $wrapper->findEmployees()
    ->joinWith('companyObject')
    ->joinWith('mainAddressObject')
    ->joinWith('roleObject')
    ->all();
```


## API Calls

The Deputy API has an inbuilt limit of a maximum of 500 records.
The wrapper will send API calls until all matching records have been returned.
The batch size can be modified on the query if needed, but note that reducing the size will increase the number of calls thus increasing execution time.

```php
$results = $wrapper->findEmployees()->batchSize(100)->all();
// if 299 records, then 3 API calls are made
// if 300 records, then 4 API calls are made
```
