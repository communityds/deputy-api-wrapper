# CHANGELOG

## 1.6.0

* Forced `Slots` in `Roster` schema to be an array ([#19](https://github.com/communityds/deputy-api-wrapper/pull/19))
* Added missing properties on `OperationalUnit` ([#18](https://github.com/communityds/deputy-api-wrapper/pull/18))
* Added `logger` service ([#20](https://github.com/communityds/deputy-api-wrapper/pull/20))
* Added missing models ([#20](https://github.com/communityds/deputy-api-wrapper/pull/20))
* Updated model `@property` and wrapper `@method` tags ([#20](https://github.com/communityds/deputy-api-wrapper/pull/20))

## 1.5.1

* Forced `MealbreakSlots` in `Timesheet` schema to be an array ([#17](https://github.com/communityds/deputy-api-wrapper/pull/17))

## 1.5.0

* Added OAuth 2.0 Access Token provider abstraction ([#13](https://github.com/communityds/deputy-api-wrapper/pull/13)).

## 1.4.0

* Added OAuth 2.0 Authentication component ([#11](https://github.com/communityds/deputy-api-wrapper/pull/11)).
* Added ability to create `Timesheet` records ([#12](https://github.com/communityds/deputy-api-wrapper/pull/12)).

## 1.3.2

* Excluding development files in production archive.

## 1.3.1

* Fixed resource name passed to UnknownDataTypeException ([#10](https://github.com/communityds/deputy-api-wrapper/pull/10)).

## 1.3.0

* Added ability to create `Memo` to assigned recipients ([#5](https://github.com/communityds/deputy-api-wrapper/pull/5)).
* Added `Roster` helper method `isTimesheetCreated` ([#7](https://github.com/communityds/deputy-api-wrapper/pull/7)).
* Added ability to change `Company` settings ([#8](https://github.com/communityds/deputy-api-wrapper/pull/8)).
* Added support for `CustomField` and `CustomFieldData` ([#9](https://github.com/communityds/deputy-api-wrapper/pull/9)).
* Fixed issue when updating `Roster` instances caused by missing payload information ([#6](https://github.com/communityds/deputy-api-wrapper/pull/6)).

## 1.2.0

* Added PHP 7 support.
* Added TravisCI integration.

## 1.1.1

* Using `Guzzle6` adapter by default.

## 1.1.0

* Added `Guzzle6` adapter.

## 1.0.1

* Added helper functions to find related objects on `Address`, `Employee` and `TrainingModule` models.

## 1.0.0

* Interact with Deputy API using model wrappers.
* Added `Guzzle3` adapter.
