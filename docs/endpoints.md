# End-points

List of Deputy API endpoints and where they have been implemented.
Taken from the [Deputy API Documentation](https://www.deputy.com/api-doc/API).

## My Calls

* Add helper functions on `Me` class that matches `my` endpoints.
* https://www.deputy.com/api-doc/API/It_is_All_About_Me

### End-points

* GET /me
    * Added to `getMe` function on the `Wrapper` class.
* GET /my/colleague
    * @todo
    * Add as `getColleagues` function on `Me` class
* GET /my/contactaddress
    * @todo
    * Add as `getContactAddress` function on `Me` class
    * Add as `getEmergencyAddress` function on `Me` class
* POST /my/contactaddress
    * @todo
    * Overload `insert` or `update` on `Address` class derived from `contactaddress` endpoint
* POST /my/contactaddress/emergency
    * @todo
    * Overload `insert` or `update` on `Address` class derived from `contactaddress` endpoint
* GET /my/contactaddress/subordinate
    * @todo
    * Add as `getSubordinates` function on `Me` class
* GET /my/leave
    * @todo
    * Add as `getLeave` function on `Me` class
* GET /my/photo
    * @todo
    * Add as `getPhoto` function on `Me` class
* GET /my/location
    * @todo
    * Add as `getLocations` function on `Me` class
* GET /my/location/:id
    * @todo
    * Add as `getLocation` function on `Me` class
* GET /my/memo
    * @todo
    * Add as `getNewsFeed` function on `Me` class
* GET /my/memo/company/:id
    * @todo
    * Add as `getNewsFeed` function on `Company` class
* GET /my/notification
    * @todo
    * Add as `getNotifications` function on `Me` class
* GET /my/pref/:prefKey
    * @todo
    * Add as `getPreference` function on `Me` class
* POST /my/pref/:prefKey
    * @todo
    * Add as `setPreference` function on `Me` class
* GET /my/roster
    * @todo
    * Add as `getRoster` function on `Me` class
* GET /my/setup
    * @todo
    * Add as `getSetup` function on `Me` class
* GET /my/tasks
    * @todo
    * Add as `getTasks` function on `Me` class
* GET /my/tasks/completed
    * @todo
    * Add as `getCompletedTasks` function on `Me` class
* GET /my/tasks/:id/do
    * @todo
    * Add as `do` function on `Task` class
* GET /my/tasks/:id/undo
    * @todo
    * Add as `undo` function on `Task` class
* GET /my/timesheet
    * @todo
    * Add as `getTimesheet` function on `Me` class
* GET /my/training
    * @todo
    * Add as `getTraining` function on `Me` class
* GET /my/unavail
    * @todo
    * Add as `getUnavailabilities` function on `Me` class

## Management Calls

* Rather than directly using the Resource API, where possible use the Management Call endpoints.
* Use the `update` end-point to update or insert a Timesheet.
* https://www.deputy.com/api-doc/API/Management_Calls
* Affected resources:
    * Contact
    * CustomField
    * CustomFieldData
    * Employee
    * EmployeeAgreement
    * EmployeeAppraisal
    * EmployeeAvailability
    * EmployeeSalaryOpunitCosting
    * EmployeeWorkspace
    * EmploymentContract
    * Event
    * Leave
    * LeaveAccrual
    * OperationalUnit
    * Roster
    * SalesData
    * Schedule
    * StressProfile
    * Task
    * TaskGroup
    * TaskGroupSetup
    * TaskSetup
    * Team
    * Timesheet
    * TimesheetPayReturn

### End-points

* GET /my/location/:id
    * See `My Calls` section.
* POST my/setup/addNewWorkplace
    * See `My Calls` section.
* POST supervise/employee
    * @todo
    * Overload `insert` on `Employee` class
* POST supervise/employee/:id
    * @todo
    * Overload `update` on `Employee` class
* POST supervise/employee/:id/activate
    * @todo
    * Add `activate` function on `Employee` class
* POST supervise/employee/:id/assoc/:company
    * @todo
    * Add `associateWith` function on `Employee` class
    * Can accept company id or `Company` object as argument
* POST supervise/employee/:id/terminate
    * @todo
    * Add `terminate` function on `Employee` class
* POST supervise/employee/:id/unassoc/:company
    * @todo
    * Add `unassociateWith` function on `Employee` class
    * Can accept company id or `Company` object as argument
* POST /supervise/empshiftinfo/:employeeId
    * @todo
    * Add `getShiftInfo` function on `Employee` class
* GET /supervise/getrecommendation/:intRosterId
    * @todo
    * Add `getRecommendation` on `Roster` class
* POST /supervise/leave
    * @todo
* POST /supervise/leave/:intEmployeeId
    * @todo
    * Add `getLeave` function on `Employee` class
* POST /supervise/journal
    * @todo
    * Not sure on what resource journals relate to
* PUT supervise/memo
    * @todo
    * Overload `insert` function on `Memo` class
* POST /supervise/roster
    * @todo
    * Overload `insert` on `Roster` class
* POST /supervise/unavail
    * @todo
    * Add `createUnavailability` on `Employee` class
* GET /supervise/unavail/:intEmployeeId
    * @todo
    * Add `getUnavailability` on `Employee` class

## Resource Calls

* https://www.deputy.com/api-doc/API/Resource_Calls

### End-points

* GET /resource/:object
    * Ignored as always using QUERY as it allows joins and ordering.
* PUT /resource/:object
    * Implemented by `Record`.
* GET /resource/:object/INFO
    * Implemented by `ResourceInfo`.
* POST /resource/:object/QUERY
    * Implemented by `Query`.
* DELETE /resource/:object/:id
    * Implemented by `Record`.
* GET /resource/:object/:id
    * Implemented by `Query` when condition is only an id.
* POST /resource/:object/:id
    * Implemented by `Record`.
* GET /resource/:object/:id/:ForeignObject
    * Implemented by `Query`.
* POST /resource/:object/:id/:ForeignObject
    * Implemented by `Record` and `Query`.
    * To update parent object would need to add event handler that updates relationship id upon save.

## Timesheet Methods

* Add helper functions on `Timesheet` resource that matches functionality within the Timesheet Calls.
* https://www.deputy.com/api-doc/API/Timesheet_Methods

### End-points

* POST /supervise/timesheet/approve
    * @todo
    * Add as `approve` function on `Timesheet` class
* POST /supervise/timesheet/discard
    * @todo
    * Overload `delete` function on `Timesheet` class
    * Add as `discard` function on `Timesheet` class
* POST /supervise/timesheet/end
    * @todo
    * Add as `end` function on `Timesheet` class
* POST /resource/Timesheet/QUERY
    * See `Resource Calls`.
* POST /supervise/timesheet/pause
    * @todo
    * Add as `pause` function on `Timesheet` class
* POST /supervise/timesheet/start
    * @todo
    * Add as `startTimesheet` function on `Employee` class
* POST /supervise/timesheet/update
    * @todo
    * Overload `insert` and `update` functions on `Timesheet` class

## Utility Methods

* https://www.deputy.com/api-doc/API/Utility_Methods

### End-points

* /execdexml/:script_id
    * @todo
* /file/upload
    * @todo
* /file/:linkid
    * @todo
* /file/:linkid/preview/:width/:height
    * @todo
* /history/:strObject/:id
    * @todo
    * Add helper function to `Record` class to return history information.
* /time
    * @todo
    * Add `currentTime` helper to `Wrapper` to return `DateTime` object.
* /time/:intCompanyId
    * @todo
    * Add `currentTime` helper to `Company` to return `DateTime` object.
* /userinfo/:id
    * Implemented by `User` class.
    
## Custom Data

* https://www.deputy.com/api-doc/API/Custom_Data

### End-points

* GET /customdata/:documentId
    * @todo
* PUT /customdata/:documentId
    * @todo
* POST /customdata/:documentId/QUERY
    * @todo
* GET /customdata/:documentId/:id
    * @todo
* POST /customdata/:documentId/:id
    * @todo
* DELETE /customdata/:documentId/:id
    * @todo

## Payroll

* https://www.deputy.com/api-doc/API/Enterprise_Payroll

### End-points

* GET /payroll
    * @todo
* GET /payroll/:Date
    * @todo
* GET /payroll/export/:CompanyPeriodId
    * @todo
    * Add as `exportPayroll` on `CompanyPeriod` class.
* POST payroll/mark/:CompanyPeriodId/paid
    * @todo
* POST payroll/mark/:CompanyPeriodId/unpaid
    * @todo
* POST payroll/mark/:CompanyPeriodId/:label
    * @todo
