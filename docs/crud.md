# Create, Read, Update and Delete

The wrapper allows resources to have standard CRUD functionality applied via an Active Record approach.

## Creating new records

* Get an empty object via the `create<Name>` method on the wrapper.
* Make changes to the record instance.
* Call the `save` method.

```php
$employee = $wrapper->createEmployee();
$employee->firstName = 'Jane';
$employee->lastName = 'Doe';
$employee->setFields(
    [
        'displayName' => 'Jane Doe',
        'active' => true,
        'allowAppraisal' => false
    ]
);
$employee->save();
echo 'Id of ' . $employee->displayName . ' is ' . $employee->id;
// Output: Id of Jane Doe is 123
```

## Reading existing records

* Get a specific record by its primary key via the `get<Name>` method on the wrapper.
* To return or filter multiple records see the [Querying Resources](query.md) documentation.

```php
$employee = $wrapper->getEmployee(3);
echo $employee->displayName . ' is an employee of ' . $employee->companyObject->companyName . ' in ' . $employee->companyObject->address->city;
// Output: Jane Doe is an employee of Deputy in Sydney
```

## Updating existing records

* Get the resource first to ensure details are up-to-date.
* Make changes to the record instance.
* Call the `save` method.

```php
$employee = $wrapper->getEmployee(3);
$employee->gender = Employee::GENDER_FEMALE;
$employee->position = 'Developer';
$employee->save();

$requests = $wrapper->findLeave()->where(['status' => 0])->all();
foreach ($requests as $leave) {
    $leave->comment = 'Will be checked soon.';
    $leave->save();
}
```

## Deleting existing records

* Call `delete<Name>` method on the wrapper:

```php
$wrapper->deleteEmployee(3);
```

* Or call `delete` method on the resource:

```php
$employee = $wrapper->getEmployee(3);
$employee->delete();

$requests = $wrapper->findLeave()->where(['status' => 3])->all(); 
foreach ($requests as $leave) {
    $leave->delete();
}
```
    