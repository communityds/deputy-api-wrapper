# CHANGELOG

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
