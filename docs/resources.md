# Resources

Resources define the fields, joins and associations on models within the Deputy API.
The schema information is populated by querying the `resource/:Name/INFO` API endpoint.

See the [Deputy Resources Documentation](https://www.deputy.com/api-doc/Resources) for a list of official resources.

## Helper Functions

Each resource has a series of helper functions added to the wrapper:

* `create<Name>`: creates empty resource instance that then uses the PUT `resource/:Name` endpoint. 
* `get<Name>`: retrieves a single record via the GET `resource/:Name/:Id` endpoint.
* `get<NamePlural>`: retrieves all records via the GET `resource/:Name` endpoint.
* `find<NamePlural>`: returns query that returns records via the POST `resource/:Name/QUERY` endpoint.
* `delete<Name>`: deletes a single record via the DELETE `resource/:Name/:Id` endpoint.

Some management or utility methods are exposed on some resources.
Check the model documentation on what additional methods can be called.

## Models

Each Resource is mapped to a Model class.
This model class acts as the Active Record and allows field values to be manipulated.

### Fields

All fields defined within the schema will be loaded into the model instance.
They can be retrieved or updated by accessing them via a case-insensitive property.
For consistency try to use camel-case when accessing them:

```php
$employee->displayName   // maps to the 'DisplayName' field
$employee->maINaddREsS   // maps to the 'MainAddress' field, but property should be camel-case
```

### Relations

Relationships are defined within Deputy as joins and accessed as Foreign Objects via the API.
Any defined relationship can be accessed via a case-insensitive property on the model instance.

```php
$employee->companyObject    // maps to the 'CompanyObject' join
$employee->companyObject->addressObject   // maps to the `AddressObject` join on the `CompanyObject` join
$employee->COmPAnYobJEct->ADdreSSobJEct   // maps to the `AddressObject` join on the `CompanyObject` join, but properties should be camel-case
```

Related objects can be modified, but must be saved independently before the primary model is saved.

```php
$employee->displayName = 'Joe Bloggs';
$employee->companyObject->companyName = 'Joe Bloggs Company';
$employee->companyObject->addressObject->city = 'Joeville';
$employee->companyObject->addressObject->save();
$employee->companyObject->save();
$employee->save();
```