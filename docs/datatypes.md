# Data Types

The Deputy API exposes the fields, joins and associations for a resource via the `resource/:Name/INFO` endpoint.
Data types are used to define how field values should be converted from the API value to the PHP value and vice versa.

## Creating a data type

* Create a class within the `Schema\DataType` folder that has the name of the data type from the Deputy API.
* Extend the `DataType` to ensure the `DataTypeInterface` interface has been implemented.
* Overload the `fromApi` function to convert the value from the API to the PHP value.
* Overload the `toApi` function to convert the value from the PHP value to the API value.
* Register the new data type within the `Registry::baseDataTypes` function.