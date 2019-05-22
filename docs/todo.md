# To Do

This is a list of features that should be added to the wrapper.



## General

### Add PHP 5.6 support

* Once the dependency on PHP 5.3 has been removed.
* Use short-hand array notation.
* Look to using Traits to share code and reduce need for statically defined helpers.
* Update versions of Composer dependencies.

### Add PHP 7 support

* Once PHP 5.6 support has been completed.
* Investigate if recent Composer dependencies can be used.



## Resources

### Caching results

* Allow some resources to be cached either persistently or at runtime.
* Check the cache if a resource Id has been provided.
* If resource is loaded from the API then automatically update the cache.
* The `Me` resource would be the first to update.

### Default Field Values

* Via the `Resource` schema, determine the default values for certain Fields.

### History

* Add helper function to `Record` class to return history information.
* Use `/history/:strObject/:id` endpoint.
* https://www.deputy.com/api-doc/API/Utility_Methods

### Relationship Ids

* Determine which Field relates to which Join or Assocation.
* When the Field value changes, the relationship needs to be removed.
* Allow any Join or Association to have an 'Id' field dynamically assigned (e.g. Company->AddressObjectId returns value of Address field).
* Typically the Field is either already a `<relation>Id` but typically it requires removal of the `Object` suffix (e.g. `AddressObject` becomes `Address`). 

## Add Relationship

* Allow adding new relations.
* When a new relation is added an event handler would update the local resource id.
* For example: adding an `AddressObject` on a `Company` would set the `Address` field.

### Eager-Loading

* When retrieving a single record, allow joins to be eagerly loaded.

### Validation Errors

* Pass through any validation errors that the API returns.
* Errors can be returned via a `getErrors` function on the model.

## Custom Data

* Allow custom data to be attached to the resource instances as if they are local properties.



## Querying

### Mass-Update

* Via a `Query` object, allow mass updates on records that match the filtering criteria.

### Mass-Assignment

* Allow multiple Fields to be assigned by adding a `setFields` method on the `Record` class.
* Also add `getField` and `setField` methods on the `Record` class.

### Eager-Loading

* Via the `Query` object, allow multiple-level relationship joins.
* Implement Eager Loading methodologies to resolve the primary keys of the joins, then use a separate Query to return them and populate into the models.



## Functionality

### Remaining endpoints

* See [End Points](endpoints.md) for status.

### File Support

* Allow files to be uploaded, retrieved and previews returned.
* See [End Points](endpoints.md) for status.
