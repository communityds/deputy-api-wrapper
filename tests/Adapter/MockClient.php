<?php

namespace CommunityDS\Deputy\Api\Tests\Adapter;

use CommunityDS\Deputy\Api\Adapter\ClientInterface;
use CommunityDS\Deputy\Api\DeputyException;
use CommunityDS\Deputy\Api\Model\Company;
use CommunityDS\Deputy\Api\WrapperLocatorTrait;

/**
 * Mocks the Deputy API to allow automated tests to occur without
 * manipulating data within Deputy.
 */
class MockClient implements ClientInterface
{
    use WrapperLocatorTrait;

    const ADDRESS_COMPANY = 12;

    const ADDRESS_COMPANY_NEW = 1245;

    const ADDRESS_FIRST = 463;

    const ADDRESS_NEW = 2365;

    const COMPANY_FIRST = 231;

    const COMPANY_NEW = 300;

    const CONTACT_COMPANY = 278;

    const COUNTRY_AUSTRALIA = 13;

    const COUNTRY_INVALID = 9999;

    const CUSTOM_FIELD_FIRST = 401;

    const CUSTOM_FIELD_SECOND = 402;

    const CUSTOM_FIELD_NEW = 422;

    const CUSTOM_FIELD_DATA_FIRST = 501;

    const EMPLOYEE_FIRST = 987235;

    const MEMO_FIRST = 432;

    const MEMO_SECOND = 321;

    const MEMO_NEW = 5432;

    const MEMO_NULL = null;

    const PORTFOLIO_FIRST = 213;

    const ROSTER_FIRST = 9283;

    const ROSTER_NEW = 92831;

    const TIMESHEET_NEW = 87623;

    const TIMESHEET_WITH_SLOTS = 1;

    const USER_ADMIN = 1;

    const USER_FIRST = 987235;

    const OP_UNIT_FIRST = 983;

    const OP_UNIT_SECOND = 523;

    const OP_UNIT_NEW = 123;

    const OAUTH_CODE_ACTIVE = 'd0d4eb04a4448bfbea671e7b19759e956e07472a';

    const OAUTH_TOKEN_ACTIVE = 'bb025805e7f1c362d8502540c5410ea4';

    const OAUTH_TOKEN_EXPIRED = '71f37de97c34bf379f5ac581f2a833fd';

    const OAUTH_REFRESH_EXPIRED = '790efd27619171636d49a03fd24f226a';

    const OAUTH_REFRESH_ACTIVE = '19df682ba5db7c613acb0b30a0a2513e';

    /**
     * Log of requests.
     *
     * @var array
     */
    private $_log = [];

    /**
     * Last error message.
     *
     * @var array
     */
    private $_error = null;

    public function getLastError()
    {
        return $this->_error;
    }

    /**
     * Returns the log of requests.
     *
     * @return array[]
     */
    public function getLog()
    {
        return $this->_log;
    }

    /**
     * Resets the request log.
     *
     * @return void
     */
    public function resetLog()
    {
        $this->_log = [];
    }

    /**
     * Returns the log of commands being executed.
     *
     * @param string $method HTTP method
     * @param string $uri URI
     *
     * @return void
     */
    public function logRequest($method, $uri)
    {
        $this->_log[] = [strtolower($method) => $uri];
    }

    /**
     * Returns expected method name.
     *
     * @param string $method HTTP method
     * @param string $uri URI
     * @param array $payload Request body
     *
     * @return string
     *
     * @throws InvalidCallException When request can not be mocked
     */
    protected function requestHandler($method, $uri, $payload = null)
    {

        // Split up requests
        $this->logRequest($method, $uri);
        $id = null;
        $foreign = null;
        $bits = explode('/', strtolower($uri));
        if ($bits[0] == 'resource') {
            if (strtolower($method) != 'put') {
                if (count($bits) == 4) {
                    $foreign = array_pop($bits);
                }
                $id = array_pop($bits);
                if ($foreign) {
                    $bits[] = $foreign;
                }
            }
        } elseif ($bits[0] == 'supervise') {
            if ($bits[1] == 'timesheet' && count($bits) == 3) {
                // Pass through complete path
            } elseif (count($bits) >= 3 && count($bits) <= 4) {
                $action = null;
                if (count($bits) == 4) {
                    $action = array_pop($bits);
                }
                $id = array_pop($bits);
                if ($action) {
                    $bits[] = $action;
                }
            }
        } elseif ($bits[0] == 'userinfo') {
            $id = array_pop($bits);
        }


        // Capture response
        try {
            $methodName = $method . implode(
                '',
                array_map(
                    function ($v) {
                        return ucfirst($v);
                    },
                    $bits
                )
            );
            if (method_exists($this, $methodName)) {
                switch (strtolower($method)) {
                    case 'oauth':
                    case 'put':
                        return $this->{$methodName}($payload);
                    case 'post':
                        if ($id === null && $foreign === null) {
                            return $this->{$methodName}($payload);
                        }
                        break;
                }
                return $this->{$methodName}($id, $payload);
            }

        // Capture manual failures
        } catch (DeputyException $e) {
            $this->_error = [
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ],
            ];
            return false;
        }

        throw new InvalidCallException('Unsupported request ' . $method . ' - ' . $uri . ' (no method ' . $methodName . ')');
    }

    public function get($uri, $successCode = null)
    {
        return $this->requestHandler('get', $uri);
    }

    public function put($uri, $payload, $successCode = null)
    {
        return $this->requestHandler('put', $uri, $payload);
    }

    public function post($uri, $payload, $successCode = null)
    {
        return $this->requestHandler('post', $uri, $payload);
    }

    public function delete($uri, $successCode = null)
    {
        return $this->requestHandler('delete', $uri);
    }

    public function postOAuth2($uri, $payload)
    {
        return $this->requestHandler(
            'oauth',
            implode('', array_map('ucfirst', explode('_', $payload['grant_type']))),
            $payload
        );
    }

    /**
     * Returns response from /me endpoint.
     *
     * @return array
     */
    protected function getMe()
    {
        return [
            'Login' => 'firstuser',
            'Name' => 'First User',
            'LastName' => 'User',
            'FirstName' => 'First',
            'Company' => static::COMPANY_FIRST,
            'CompanyObject' => $this->getResourceCompany(static::COMPANY_FIRST),
            'Portfolio' => 'deputywrapper',
            'DeputyVersion' => '3.0.7',
            'UserId' => static::USER_FIRST,
            'EmployeeId' => static::EMPLOYEE_FIRST,
            'PrimaryEmail' => 'deputy@communityds.com.au',
            'PrimaryPhone' => '0812345678',
            'Permissions' => [
                'Can_View_Staff_Roles',
                'Can_View_Training_Modules',
            ],
            'JournalCategories' => [
                [
                    'Id' => 1,
                    'Category' => 'Positive',
                    'Group' => 'Rating',
                    'SortOrder' => 1,
                    'Stafflog' => true,
                    'System' => false,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2013-03-15T08:53:26+10:30',
                    'Modified' => '2013-03-14T21:53:26+10:30',
                ],
            ],
            'InProgressTS' => null,
            'UserSince' => '2018-04-24T16:15:25+09:30',
            'UserObjectForAPI' => $this->getUserinfo(static::USER_FIRST),
            'OPS' => [],
            'MemosToConfirm' => [],
        ];
    }

    /**
     * Returns response from GET /resource/Address/:Id endpoint.
     *
     * @param integer $id Employee id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceAddress($id)
    {
        switch ($id) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'ContactName' => 'VarChar',
                        'UnitNo' => 'VarChar',
                        'StreetNo' => 'VarChar',
                        'SuiteNo' => 'VarChar',
                        'PoBox' => 'VarChar',
                        'Street1' => 'VarChar',
                        'Street2' => 'VarChar',
                        'City' => 'VarChar',
                        'State' => 'VarChar',
                        'Postcode' => 'VarChar',
                        'Country' => 'Integer',
                        'Phone' => 'VarChar',
                        'Notes' => 'Blob',
                        'Format' => 'Integer',
                        'Saved' => 'Bit',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                    ],
                    'joins' => [],
                    'assocs' => [],
                    'count' => 0,
                ];
            case static::ADDRESS_COMPANY:
                return [
                    'Id' => static::ADDRESS_COMPANY,
                    'ContactName' => null,
                    'UnitNo' => null,
                    'StreetNo' => null,
                    'SuiteNo' => null,
                    'PoBox' => null,
                    'Street1' => '3/214 Greenhill Road, EASTWOOD SA 5063',
                    'Street2' => null,
                    'City' => 'EASTWOOD',   // company addresses generally have all info in street1, but it's here for unit testing
                    'State' => null,
                    'Postcode' => null,
                    'Country' => static::COUNTRY_AUSTRALIA,
                    'Phone' => '0812345678',    // company addresses generally do not have phone numbers, but it's here for unit testing
                    'Notes' => "Line #1\nLine #2",
                    'Format' => null,
                    'Saved' => null,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2013-03-15T08:53:26+10:30',
                    'Modified' => '2013-03-15T08:53:26+10:30',
                ];
            case static::ADDRESS_COMPANY_NEW:
                return [
                    'Id' => static::ADDRESS_COMPANY_NEW,
                    'ContactName' => null,
                    'UnitNo' => null,
                    'StreetNo' => null,
                    'SuiteNo' => null,
                    'PoBox' => null,
                    'Street1' => '214 Greenhill Road, Eastwood SA 5063',
                    'Street2' => null,
                    'City' => null,
                    'State' => null,
                    'Postcode' => null,
                    'Country' => static::COUNTRY_AUSTRALIA,
                    'Phone' => null,
                    'Notes' => 'Company Notes',
                    'Format' => null,
                    'Saved' => null,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2013-03-15T08:53:26+10:30',
                    'Modified' => '2013-03-15T08:53:26+10:30',
                ];
            case static::ADDRESS_FIRST:
                return [
                    'Id' => static::ADDRESS_FIRST,
                    'ContactName' => null,
                    'UnitNo' => null,
                    'StreetNo' => '2',
                    'SuiteNo' => null,
                    'PoBox' => null,
                    'Street1' => '2 Greenhill Road',
                    'Street2' => null,
                    'City' => 'EASTWOOD',
                    'State' => 'SA',
                    'Postcode' => '5063',
                    'Country' => static::COUNTRY_AUSTRALIA,
                    'Phone' => '1234567890',
                    'Notes' => null,
                    'Format' => null,
                    'Saved' => null,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2018-04-27T16:15:30+09:30',
                    'Modified' => '2018-04-27T16:15:30+09:30',
                ];
            case static::ADDRESS_NEW:
                return [
                    'Id' => static::ADDRESS_NEW,
                    'ContactName' => null,
                    'UnitNo' => null,
                    'StreetNo' => null,
                    'SuiteNo' => null,
                    'PoBox' => null,
                    'Street1' => 'New Address',
                    'Street2' => null,
                    'City' => null,
                    'State' => null,
                    'Postcode' => null,
                    'Country' => static::COUNTRY_AUSTRALIA,
                    'Phone' => null,
                    'Notes' => null,
                    'Format' => null,
                    'Saved' => null,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2018-04-27T16:15:30+09:30',
                    'Modified' => '2018-04-27T16:15:30+09:30',
                ];
        }
        throw new InvalidCallException('Unknown test address record #' . $id);
    }

    /**
     * Returns response from POST /resource/Address/:Id endpoint.
     *
     * @param integer $id Address id
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     * @throws MockErrorException When `Country` matches value of `COUNTRY_INVALID` constant
     */
    protected function postResourceAddress($id, $payload)
    {
        switch ($id) {
            case 'query':
                return $this->postResourceAddressQuery($payload);
            case static::ADDRESS_FIRST:
            case static::ADDRESS_COMPANY:
                $country = isset($payload['Country']) ? $payload['Country'] : null;
                if ($country == static::COUNTRY_INVALID) {
                    throw new MockErrorException('Manually triggering failure', 500);
                }
                return array_merge(
                    $this->getResourceAddress($id),
                    $payload
                );
        }
        throw new InvalidCallException('Unknown test address record #' . $id);
    }

    /**
     * Returns response from POST /resource/Address/QUERY endpoint.
     *
     * @param array $payload Query data
     *
     * @return array
     *
     * @throws InvalidCallException When unknown search condition encountered
     */
    protected function postResourceAddressQuery($payload)
    {
        $result = [];
        $result[] = $this->getResourceAddress(static::ADDRESS_COMPANY);
        $result[] = $this->getResourceAddress(static::ADDRESS_FIRST);

        if (array_key_exists('start', $payload) && $payload['start'] > 0) {
            $result = array_slice($result, $payload['start']);
        }
        if (array_key_exists('max', $payload) && $payload['max'] > 0) {
            $result = array_slice($result, 0, $payload['max']);
        }

        if (array_key_exists('search', $payload)) {
            foreach ($payload['search'] as $condition) {
                if ($condition['field'] == 'Created' && $condition['type'] == 'ge') {
                    $result = array_filter(
                        $result,
                        function ($v) use ($condition) {
                            $val = new \DateTime($v['Created']);
                            $con = new \DateTime($condition['data']);
                            return $val->getTimestamp() >= $con->getTimestamp();
                        }
                    );
                } elseif ($condition['field'] == 'Phone' && $condition['type'] == 'in') {
                    $result = array_filter(
                        $result,
                        function ($v) use ($condition) {
                            return in_array($v['Phone'], $condition['data']);
                        }
                    );
                } elseif ($condition['field'] == 'City' && $condition['type'] == 'eq') {
                    $result = array_filter(
                        $result,
                        function ($v) use ($condition) {
                            return strtolower($v['City']) == strtolower($condition['data']);
                        }
                    );
                } else {
                    throw new InvalidCallException('Unknown search condition: ' . var_export($condition, true));
                }
            }
        }

        if (array_key_exists('sort', $payload)) {
            $condition = $payload['sort'];
            usort(
                $result,
                function ($left, $right) use ($condition) {
                    foreach ($condition as $name => $dir) {
                        $pos = strcasecmp(
                            $left[$name],
                            $right[$name]
                        );
                        if ($pos !== 0) {
                            return $dir === 'asc' ? $pos : (-1 * $pos);
                        }
                    }
                    return 0;
                }
            );
        }

        return $result;
    }

    /**
     * Returns response from PUT /resource/Address endpoint.
     *
     * @param array $payload Query data
     *
     * @return array
     *
     * @throws InvalidCallException When address is unknown
     * @throws MockErrorException When `Country` is value of `COUNTRY_INVALID` constant
     */
    protected function putResourceAddress($payload)
    {
        $country = isset($payload['Country']) ? $payload['Country'] : null;
        switch ($country) {
            case static::COUNTRY_AUSTRALIA:
                return $this->getResourceAddress(static::ADDRESS_NEW);
            case static::COUNTRY_INVALID:
                throw new MockErrorException('Manually forcing failure', 500);
        }
        throw new InvalidCallException('Unknown address: ' . $country);
    }

    /**
     * Returns response from DELETE /resource/Address/:Id endpoint.
     *
     * @param integer $id Address id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     * @throws MockErrorException When id is value of `ADDRESS_COMPANY` constant
     */
    protected function deleteResourceAddress($id)
    {
        switch ($id) {
            case static::ADDRESS_FIRST:
                return ['message' => 'Delete ' . $id];
            case static::ADDRESS_COMPANY:
                throw new MockErrorException('Manually forcing failure', 500);
        }
        throw new InvalidCallException('Unknown company: ' . $id);
    }

    /**
     * Returns response from GET /resource/Company/:Id endpoint.
     *
     * @param integer $id Company id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceCompany($id)
    {
        switch ($id) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'Portfolio' => 'Integer',
                        'Code' => 'VarChar',
                        'Active' => 'Bit',
                        'ParentCompany' => 'Integer',
                        'CompanyName' => 'VarChar',
                        'TradingName' => 'VarChar',
                        'BusinessNumber' => 'VarChar',
                        'CompanyNumber' => 'VarChar',
                        'IsWorkplace' => 'Bit',
                        'IsPayrollEntity' => 'Bit',
                        'PayrollExportCode' => 'VarChar',
                        'Address' => 'Integer',
                        'Contact' => 'Integer',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                    ],
                    'joins' => [
                        'ParentCompanyObject' => 'Company',
                        'AddressObject' => 'Address',
                        'ContactObject' => 'Contact',
                    ],
                    'assocs' => [
                        'Team' => 'Team',
                        'Company' => 'Memo',
                    ],
                    'count' => 0,
                ];
            case static::COMPANY_FIRST:
                return [
                    'Id' => static::COMPANY_FIRST,
                    'Portfolio' => static::PORTFOLIO_FIRST,
                    'Code' => 'ADE',
                    'Active' => true,
                    'ParentCompany' => null,
                    'CompanyName' => 'First Company',
                    'TradingName' => null,
                    'BusinessNumber' => '0812345678',
                    'CompanyNumber' => '0887654321',
                    'IsWorkplace' => true,
                    'IsPayrollEntity' => false,
                    'Address' => static::ADDRESS_COMPANY,
                    'Contact' => static::CONTACT_COMPANY,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2013-03-15T08:53:26+10:30',
                    'Modified' => '2013-03-15T08:53:26+10:30',
                ];
            case static::COMPANY_NEW:
                return [
                    'Id' => static::COMPANY_NEW,
                    'Portfolio' => static::PORTFOLIO_FIRST,
                    'Code' => 'NEW',
                    'Active' => false,
                    'ParentCompany' => null,
                    'CompanyName' => 'New Company',
                    'TradingName' => null,
                    'BusinessNumber' => null,
                    'CompanyNumber' => null,
                    'IsWorkplace' => false,
                    'IsPayrollEntity' => false,
                    'Address' => static::ADDRESS_COMPANY_NEW,
                    'Contact' => null,
                    'Creator' => static::USER_FIRST,
                    'Created' => '2013-03-15T08:53:26+10:30',
                    'Modified' => '2013-03-15T08:53:26+10:30',
                ];
        }
        throw new InvalidCallException('Unknown test company record #' . $id);
    }

    /**
     * Returns response from GET /resource/Company/:Id/AddressObject endpoint.
     *
     * @param string $id Company id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceCompanyAddressobject($id)
    {
        switch ($id) {
            case static::COMPANY_FIRST:
                return $this->getResourceAddress(static::ADDRESS_COMPANY);
            case static::COMPANY_NEW:
                return $this->getResourceAddress(static::ADDRESS_COMPANY_NEW);
        }
        throw new InvalidCallException('Unknown address object relationship for company id ' . $id);
    }

    /**
     * Returns response from POST /resource/Company/:Id/AddressObject endpoint.
     *
     * @param string $id Company id
     * @param array $payload POST payload
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function postResourceCompanyAddressobject($id, $payload)
    {
        switch ($id) {
            case static::COMPANY_FIRST:
                return array_merge(
                    $this->getResourceAddress(static::ADDRESS_COMPANY),
                    $payload
                );
        }
        throw new InvalidCallException('Unknown address object relationship for company id ' . $id);
    }

    /**
     * Returns response from POST /resource/Company/:Id endpoint
     *
     * @param integer $id Company id
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     * @throws MockErrorException When `CompanyName` is `FAIL`
     */
    protected function postResourceCompany($id, $payload)
    {
        switch ($id) {
            case 'query':
                return $this->postResourceCompanyQuery($payload);
            case static::COMPANY_FIRST:
                $companyName = isset($payload['CompanyName']) ? $payload['CompanyName'] : null;
                if ($companyName !== null) {
                    if ($companyName == 'FAIL') {
                        throw new MockErrorException('Manually triggering failure', 500);
                    }
                    return array_merge(
                        $this->getResourceCompany($id),
                        [
                            'CompanyName' => $companyName,
                        ]
                    );
                }
                break;
        }
        throw new InvalidCallException('Unknown company object #' . $id);
    }

    /**
     * Returns response from POST /resource/Company/QUERY endpoint.
     *
     * @param array $payload Query data
     *
     * @return array
     */
    protected function postResourceCompanyQuery($payload)
    {
        $results = [];
        $results[] = $this->getResourceCompany(static::COMPANY_FIRST);

        if (array_key_exists('join', $payload)) {
            foreach ($payload['join'] as $name) {
                switch ($name) {
                    case 'AddressObject':
                        foreach ($results as $key => $result) {
                            $results[$key]['AddressObject'] = $this->getResourceAddress($result['Address']);
                        }
                        break;
                }
            }
        }

        return $results;
    }

    /**
     * Returns response from PUT /resource/Company endpoint.
     *
     * @param array $payload Query data
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     * @throws MockErrorException When `Code` is `FAIL`
     */
    protected function putResourceCompany($payload)
    {
        $code = isset($payload['Code']) ? $payload['Code'] : null;
        switch ($code) {
            case 'NEW':
                return $this->getResourceCompany(static::COMPANY_NEW);
            case 'FAIL':
                throw new MockErrorException('Manually forcing failure', 500);
        }
        throw new InvalidCallException('Unknown company code: ' . $code);
    }

    /**
     * Returns response from DELETE /resource/Company/:Id endpoint.
     *
     * @param integer $id Company id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     * @throws MockErrorException When id is value of the `COMPANY_NEW` constant
     */
    protected function deleteResourceCompany($id)
    {
        switch ($id) {
            case static::COMPANY_FIRST:
                return ['message' => 'Delete ' . $id];
            case static::COMPANY_NEW:
                throw new MockErrorException('Manually forcing failure', 500);
        }
        throw new InvalidCallException('Unknown company: ' . $id);
    }

    /**
     * Returns response to GET /resource/company/:Id/settings endpoint.
     *
     * @param mixed $id Company id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceCompanySettings($id)
    {
        switch ($id) {
            case static::COMPANY_NEW:
            case static::COMPANY_FIRST:
                return [
                    Company::SETTING_ACTIVE_HOURS_END => "00:00",
                    Company::SETTING_ACTIVE_HOURS_START => "09:00",
                    Company::SETTING_AUTO_SUGGEST_BREAK => true,
                    Company::SETTING_CAN_BUMP_SHIFT_VIA_DESK => true,
                    Company::SETTING_CAN_CLOCKIN_SHIFT_EARLIER => true,
                    Company::SETTING_CAN_CLOCKIN_SHIFT_EARLIER_MINS => 5,
                    Company::SETTING_CAN_DISPLAY_BREAK_WARNING => false,
                    Company::SETTING_CAN_END_BREAK_EARLIER => true,
                    Company::SETTING_CAN_MOBILE_BUMP_SHIFT => true,
                    Company::SETTING_CAN_MODIFY_TIMESHEET_ON_END => true,
                    Company::SETTING_CAN_SMS_BUMP_SHIFT => false,
                    Company::SETTING_CAN_SUBMIT_SHIFT_VIA_DESK => true,
                    Company::SETTING_DEFAULT_MEALBREAK_DURATION => 0,
                    Company::SETTING_MEALBREAK_IS_PAID => false,
                    Company::SETTING_REQUIRE_KIOSK_PHOTO_BUMP_SHIFT => true,
                    Company::SETTING_ROSTER_ALLOW_OFFER_SHIFT => false,
                    Company::SETTING_ROSTER_ALLOW_PEER_VIEW => 1,
                    Company::SETTING_ROSTER_ALLOW_SMS_WITH_FULL_NAME => false,
                    Company::SETTING_ROSTER_ALLOW_SWAP_SHIFT => false,
                    Company::SETTING_ROSTER_DEFAULT_SHIFT_LEN => 6,
                    Company::SETTING_ROSTER_NOTIFICATION_MANAGER => 2,
                    Company::SETTING_ROSTER_PREVENT_CHANGE_HOURS => 72,
                    Company::SETTING_ROSTER_RECOMMENDATION_SORTING => "BEST",
                    Company::SETTING_ROSTER_REQUIRE_CONFIRM_HOURS => 336,
                    Company::SETTING_ROSTER_SWAP_REQUIRE_APPROVAL => false,
                    Company::SETTING_SHIFT_COST_ADDITIONAL => 30,
                    Company::SETTING_TIMESHEET_AUTO_ROUND => false,
                    Company::SETTING_TIMESHEET_AUTO_TIME_APPROVE => 0,
                    Company::SETTING_TIMESHEET_CLOSEST_BLOCK => 15,
                    Company::SETTING_TIMESHEET_MATCH_ROSTER => 0,
                    Company::SETTING_TIMESHEET_MATCH_ROSTER_TIME => 0,
                    Company::SETTING_TIMESHEET_MATURITY => 15,
                    Company::SETTING_TIMESHEET_ROUND_END_TIME => "c15",
                    Company::SETTING_TIMESHEET_ROUND_END_TIME_RS => false,
                    Company::SETTING_TIMESHEET_ROUND_MEALBREAK => "c15",
                    Company::SETTING_TIMESHEET_ROUND_MEALBREAK_RS => false,
                    Company::SETTING_TIMESHEET_ROUND_START_TIME => "c15",
                    Company::SETTING_TIMESHEET_ROUND_START_TIME_RS => false,
                    Company::SETTING_WEEK_START => 1,
                ];
        }
        throw new InvalidCallException('Unknown CompanySetting id ' . $id);
    }

    /**
     * Returns response to POST /resource/company/:Id/settings endpoint.
     *
     * @param integer $id Company id
     * @param array $payload POST data
     *
     * @return boolean
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function postSuperviseCompanySettings($id, $payload)
    {
        switch ($id) {
            case static::COMPANY_NEW:
            case static::COMPANY_FIRST:
                return true;
        }
        throw new InvalidCallException('Unknown Company id ' . $id);
    }

    /**
     * Returns response to GET /resource/company/:Id/settings endpoint.
     *
     * @param integer $id Company id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getSuperviseCompanySettings($id)
    {
        return $this->getResourceCompanySettings($id);
    }

    /**
     * Returns response from GET /resource/CustomField/:id endpoint.
     *
     * @param string $id CustomField id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceCustomfield($id)
    {
        switch (strtolower($id)) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'System' => 'VarChar',
                        'Name' => 'VarChar',
                        'ApiName' => 'VarChar',
                        'DeputyField' => 'VarChar',
                        'SortOrder' => 'Integer',
                        'Default' => 'VarChar',
                        'Type' => 'Integer',
                        'Valuelist' => 'Blob',
                        'TriggerScript' => 'Integer',
                        'Validation' => 'VarChar',
                        'Helptext' => 'VarChar',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                    ],
                    'joins' => [],
                    'assocs' => [],
                    'count' => 0,
                ];
            case static::CUSTOM_FIELD_NEW:
                return [
                    'Id' => $id,
                    'System' => 'Timesheet',
                    'Name' => 'New Custom Field',
                    'ApiName' => 'casenotes',
                    'DeputyField' => 'f03',
                    'SortOrder' => 3,
                    'Default' => '',
                    'Type' => 2,
                    'Valuelist' => '[]',
                    'TriggerScript' => 0,
                    'Validation' => '[
                                        "nempty"
                                    ]',
                    'Helptext' => 'Help Text',
                    'Creator' => static::USER_FIRST,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                ];
            case static::CUSTOM_FIELD_FIRST:
                return [
                    'Id' => $id,
                    'System' => 'Timesheet',
                    'Name' => 'Travel Distance',
                    'ApiName' => 'traveldistance',
                    'DeputyField' => 'f01',
                    'SortOrder' => 1,
                    'Default' => '',
                    'Type' => 2,
                    'Valuelist' => '[]',
                    'TriggerScript' => 0,
                    'Validation' => '[
                                        "nempty"
                                    ]',
                    'Helptext' => 'Help Text',
                    'Creator' => static::USER_FIRST,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                ];
            case static::CUSTOM_FIELD_SECOND:
                return [
                    'Id' => $id,
                    'System' => 'Timesheet',
                    'Name' => 'Travel Time',
                    'ApiName' => 'traveltime',
                    'DeputyField' => 'f02',
                    'SortOrder' => 2,
                    'Default' => '',
                    'Type' => 2,
                    'Valuelist' => '[]',
                    'TriggerScript' => 0,
                    'Validation' => '[
                                        "nempty"
                                    ]',
                    'Helptext' => 'Help Text',
                    'Creator' => static::USER_FIRST,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                ];
        }
        throw new InvalidCallException('Unknown customField unid ' . $id);
    }

    /**
     * Returns response from POST /resource/CustomField/:Id endpoint
     *
     * @param integer $id CustomField id
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function postResourceCustomfield($id, $payload)
    {
        switch ($id) {
            case 'info':
                return $this->postResourceCustomfieldQuery($payload);
            case 'query':
                return $this->postResourceCustomfieldQuery($payload);
            case self::CUSTOM_FIELD_NEW:
            case self::CUSTOM_FIELD_FIRST:
            case self::CUSTOM_FIELD_SECOND:
                return [$this->getResourceCustomfield($id)];
        }
        throw new InvalidCallException('Unknown customField object #' . $id);
    }

    /**
     * Returns response from POST /resource/CustomField/QUERY endpoint.
     *
     * @param array $payload Query data
     *
     * @return array
     */
    protected function postResourceCustomfieldQuery($payload)
    {
        $queryLimit = isset($payload['max']) ? $payload['max'] : null;

        if ($queryLimit == 1) {
            return [$this->getResourceCustomfield(MockClient::CUSTOM_FIELD_FIRST)];
        }

        return [
            $this->getResourceCustomfield(MockClient::CUSTOM_FIELD_FIRST),
            $this->getResourceCustomfield(MockClient::CUSTOM_FIELD_SECOND),
        ];
    }

    /**
     * Returns response to PUT /resource/CustomField endpoint.
     *
     * @param array $payload PUT payload
     *
     * @return array
     *
     * @throws InvalidCallException When payload is unknown
     * @throws MockErrorException When $name is 'Invalid Custom Field'
     */
    protected function putResourceCustomfield($payload)
    {
        $name = isset($payload['Name']) ? $payload['Name'] : null;
        switch ($name) {
            case 'New Custom Field':
                return $this->getResourceCustomfield(static::CUSTOM_FIELD_NEW);
            case 'Update Custom Field':
                return $this->getResourceCustomfield(static::CUSTOM_FIELD_FIRST);
            case 'Invalid Custom Field':
                throw new MockErrorException('Manually forcing failure', 500);
        }
        throw new InvalidCallException('Unknown customField payload: ' . var_export($payload, true));
    }

    /**
     * Returns response from GET /resource/CustomFieldData/:id endpoint.
     *
     * @param string $id CustomFieldData id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceCustomfielddata($id)
    {
        switch (strtolower($id)) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'System' => 'VarChar',
                        'F01' => 'Blob',
                        'F02' => 'Blob',
                        'F03' => 'Blob',
                        'F04' => 'Blob',
                        'F05' => 'Blob',
                        'F06' => 'Blob',
                        'F07' => 'Blob',
                        'F08' => 'Blob',
                        'F09' => 'Blob',
                        'F10' => 'Blob',
                        'F11' => 'Blob',
                        'F12' => 'Blob',
                        'F13' => 'Blob',
                        'F14' => 'Blob',
                        'F15' => 'Blob',
                        'F16' => 'Blob',
                        'F17' => 'Blob',
                        'F18' => 'Blob',
                        'F19' => 'Blob',
                        'F20' => 'Blob',
                        'F21' => 'Blob',
                        'F22' => 'Blob',
                        'F23' => 'Blob',
                        'F24' => 'Blob',
                        'F25' => 'Blob',
                        'F26' => 'Blob',
                        'F27' => 'Blob',
                        'F28' => 'Blob',
                        'F29' => 'Blob',
                        'F30' => 'Blob',
                        'F31' => 'Blob',
                        'F32' => 'Blob',
                        'F33' => 'Blob',
                        'F34' => 'Blob',
                        'F35' => 'Blob',
                        'F36' => 'Blob',
                        'F37' => 'Blob',
                        'F38' => 'Blob',
                        'F39' => 'Blob',
                        'F40' => 'Blob',
                        'F41' => 'Blob',
                        'F42' => 'Blob',
                        'F43' => 'Blob',
                        'F44' => 'Blob',
                        'F45' => 'Blob',
                        'F46' => 'Blob',
                        'F47' => 'Blob',
                        'F48' => 'Blob',
                        'F49' => 'Blob',
                        'F50' => 'Blob',
                        'F51' => 'Blob',
                        'F52' => 'Blob',
                        'F53' => 'Blob',
                        'F54' => 'Blob',
                        'F55' => 'Blob',
                        'F56' => 'Blob',
                        'F57' => 'Blob',
                        'F58' => 'Blob',
                        'F59' => 'Blob',
                        'F60' => 'Blob',
                        'F61' => 'Blob',
                        'F62' => 'Blob',
                        'F63' => 'Blob',
                        'F64' => 'Blob',
                        'F65' => 'Blob',
                        'F66' => 'Blob',
                        'F67' => 'Blob',
                        'F68' => 'Blob',
                        'F69' => 'Blob',
                        'F70' => 'Blob',
                        'F71' => 'Blob',
                        'F72' => 'Blob',
                        'F73' => 'Blob',
                        'F74' => 'Blob',
                        'F75' => 'Blob',
                        'F76' => 'Blob',
                        'F77' => 'Blob',
                        'F78' => 'Blob',
                        'F79' => 'Blob',
                        'F80' => 'Blob',
                        'F81' => 'Blob',
                        'F82' => 'Blob',
                        'F83' => 'Blob',
                        'F84' => 'Blob',
                        'F85' => 'Blob',
                        'F86' => 'Blob',
                        'F87' => 'Blob',
                        'F88' => 'Blob',
                        'F89' => 'Blob',
                        'F90' => 'Blob',
                        'F91' => 'Blob',
                        'F92' => 'Blob',
                        'F93' => 'Blob',
                        'F94' => 'Blob',
                        'F95' => 'Blob',
                        'F96' => 'Blob',
                        'F97' => 'Blob',
                        'F98' => 'Blob',
                        'F99' => 'Blob',
                        'F100' => 'Blob',
                        'F101' => 'Blob',
                        'F102' => 'Blob',
                        'F103' => 'Blob',
                        'F104' => 'Blob',
                        'F105' => 'Blob',
                        'F106' => 'Blob',
                        'F107' => 'Blob',
                        'F108' => 'Blob',
                        'F109' => 'Blob',
                        'F110' => 'Blob',
                        'F111' => 'Blob',
                        'F112' => 'Blob',
                        'F113' => 'Blob',
                        'F114' => 'Blob',
                        'F115' => 'Blob',
                        'F116' => 'Blob',
                        'F117' => 'Blob',
                        'F118' => 'Blob',
                        'F119' => 'Blob',
                        'F120' => 'Blob',
                        'F121' => 'Blob',
                        'F122' => 'Blob',
                        'F123' => 'Blob',
                        'F124' => 'Blob',
                        'F125' => 'Blob',
                        'F126' => 'Blob',
                        'F127' => 'Blob',
                        'F128' => 'Blob',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                    ],
                    'joins' => [],
                    'assocs' => [],
                    'count' => 1,
                ];
            case static::CUSTOM_FIELD_DATA_FIRST:
                return [
                    'Id' => static::CUSTOM_FIELD_DATA_FIRST,
                    'System' => 'Timesheet',
                    'F01' => '10',
                    'F02' => '20',
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                    'traveldistance' => '10',
                    'traveltime' => '20',
                ];
        }
        throw new InvalidCallException('Unknown customFieldData id ' . $id);
    }

    /**
     * Returns response from GET /userinfo/:Id endpoint.
     *
     * @param integer $id User id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getUserinfo($id)
    {
        switch ($id) {
            case static::USER_ADMIN:
                return [
                    'Id' => static::USER_ADMIN,
                    'DisplayName' => 'Administrator',
                    'Employee' => static::USER_ADMIN,
                    'Photo' => '',
                ];
            case static::USER_FIRST:
                return [
                    'Id' => static::USER_FIRST,
                    'DisplayName' => 'First User',
                    'Employee' => static::EMPLOYEE_FIRST,
                    'Photo' => '',
                ];
        }
        throw new InvalidCallException('Unknown test user record #' . $id);
    }

    /**
     * Returns response from POST /my/setup/addNewWorkplace endpoint.
     *
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When payload has unexpected contents
     * @throws MockErrorException When `strWorkplaceCode` is `FAIL`
     */
    protected function postMySetupAddnewworkplace($payload)
    {
        $code = isset($payload['strWorkplaceCode']) ? $payload['strWorkplaceCode'] : null;
        switch ($code) {
            case 'NEW':
                return $this->getResourceCompany(static::COMPANY_NEW);
            case 'FAIL':
                throw new MockErrorException('Manually forcing failure', 500);
        }
        throw new InvalidCallException('Unexpected payload: ' . var_export($payload, true));
    }

    /**
     * Returns response to GET /resource/Memo/:Id endpoint.
     *
     * @param integer $id Memo id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceMemo($id)
    {
        if ($id === static::MEMO_NULL) {
            return [
                'Id' => null,
                'ShowFrom' => '2018-08-01T00:00:00+09:30',
                'Active' => true,
                'ShowTill' => null,
                'Title' => null,
                'Content' => 'New Memo Content',
                'Type' => 1,
                'File' => null,
                'Url' => null,
                'ConfirmText' => '',
                'Keyword' => null,
                'Creator' => static::USER_ADMIN,
                'Created' => '2018-08-01T14:36:34+09:30',
                'Modified' => '2018-08-01T14:36:34+09:30',
            ];
        }

        switch (strtolower($id)) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'ShowFrom' => 'Date',
                        'Active' => 'Bit',
                        'ShowTill' => 'Date',
                        'Title' => 'VarChar',
                        'Content' => 'Blob',
                        'Type' => 'Integer',
                        'File' => 'Integer',
                        'Url' => 'VarChar',
                        'ConfirmText' => 'VarChar',
                        'Keyword' => 'Blob',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                    ],
                    'joins' => [],
                    'assocs' => [
                        'Company' => 'Company',
                        'Role' => 'EmployeeRole',
                        'Team' => 'Team',
                    ],
                    'count' => 0,
                ];
            case static::MEMO_FIRST:
                return [
                    'Id' => $id,
                    'ShowFrom' => '2018-07-31T00:00:00+09:30',
                    'Active' => true,
                    'ShowTill' => null,
                    'Title' => null,
                    'Content' => 'First Memo Content',
                    'Type' => 1,
                    'File' => null,
                    'Url' => null,
                    'ConfirmText' => '',
                    'Keyword' => 'First Memo Content',
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2018-07-31T14:36:34+09:30',
                    'Modified' => '2018-07-31T14:36:34+09:30',
                ];
            case static::MEMO_NEW:
                return [
                    'Id' => $id,
                    'ShowFrom' => '2018-08-01T00:00:00+09:30',
                    'Active' => true,
                    'ShowTill' => null,
                    'Title' => null,
                    'Content' => 'New Memo Content',
                    'Type' => 1,
                    'File' => null,
                    'Url' => null,
                    'ConfirmText' => '',
                    'Keyword' => 'New Memo Content',
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2018-08-01T14:36:34+09:30',
                    'Modified' => '2018-08-01T14:36:34+09:30',
                ];
        }
        throw new InvalidCallException('Unknown memo id ' . $id);
    }

    /**
     * Returns response from POST /resource/Memo/:Id endpoint - not supported
     *
     * @param integer $id Memo id
     * @param array $payload POST data
     *
     * @throws DeputyException When attempting to update a Memo - not supported
     */
    protected function postResourceMemo($id, $payload)
    {
        throw new DeputyException('Resource not allowed for modification', 404);
    }

    /**
     * Returns response from POST /supervise/memo endpoint.
     *
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws MockErrorException When payload has no Company recipients
     */
    protected function postSuperviseMemo($payload)
    {
        $content = isset($payload['strContent']) ? $payload['strContent'] : null;
        $assignedCompanyIds = isset($payload['arrAssignedCompanyIds']) ? $payload['arrAssignedCompanyIds'] : null;
        $assignedUserIds = isset($payload['arrAssignedUserIds']) ? $payload['arrAssignedUserIds'] : null;

        // Handle no recipients
        if (empty($assignedCompanyIds) && empty($assignedUserIds)) {
            throw new MockErrorException('Sorry, you need to select some active Location or some employed people.', 400);
        }

        // Handle no content - Deputy API returns Memo with id as null when no content is provided
        if (empty($content)) {
            return $this->getResourceMemo(static::MEMO_NULL);
        }

        return $this->getResourceMemo(static::MEMO_NEW);
    }

    /**
     * Returns response from GET /resource/OperationalUnit/:id endpoint.
     *
     * @param string $id Unit id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceOperationalunit($id)
    {
        switch (strtolower($id)) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                        'Company' => 'Integer',
                        'ParentOperationalUnit' => 'Integer',
                        'OperationalUnitName' => 'VarChar',
                        'Active' => 'Bit',
                        'PayrollExportName' => 'VarChar',
                        'Address' => 'Integer',
                        'Contact' => 'Integer',
                        'RosterSortOrder' => 'Integer',
                        'ShowOnRoster' => 'Bit',
                        'Colour' => 'VarChar',
                        'RosterActiveHoursSchedule' => 'Integer',
                        'DailyRosterBudget' => 'Float',
                    ],
                    'joins' => [
                        'CompanyObject' => 'Company',
                        'ParentOperationalUnitObject' => 'OperationalUnit',
                        'AddressObject' => 'Address',
                        'ContactObject' => 'Contact',
                        'RosterActiveHoursScheduleObject' => 'Schedule',
                    ],
                    'assocs' => [
                        'OperationUnit' => 'PublicHoliday',
                        'EmployeeSalaryOpunits' => 'EmployeeAgreement',
                        'OperationalUnit' => 'Event',
                        'ManagementEmployeeOperationalUnit' => 'Employee',
                        'TrainingModule' => 'TrainingModule',
                        'RosterEmployeeOperationalUnit' => 'Employee',
                        'TaskGroupOpUnit' => 'TaskGroupSetup',
                    ],
                    'count' => 45,
                ];
            case static::OP_UNIT_NEW:
                return [
                    'Id' => $id,
                    'Creator' => static::USER_FIRST,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                    'Company' => static::COMPANY_FIRST,
                    'ParentOperationalUnit' => 0,
                    'OperationalUnitName' => 'New Unit',
                    'Active' => true,
                    'PayrollExportName' => null,
                    'Address' => static::ADDRESS_COMPANY,
                    'Contact' => null,
                    'RosterSortOrder' => null,
                    'ShowOnRoster' => true,
                    'Colour' => null,
                    'RosterActiveHoursSchedule' => null,
                    'DailyRosterBudget' => null,
                    'CompanyCode' => 'ADE',
                    'CompanyName' => 'First Company',
                ];
            case static::OP_UNIT_FIRST:
                return [
                    'Id' => $id,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                    'Company' => static::COMPANY_FIRST,
                    'ParentOperationalUnit' => 0,
                    'OperationalUnitName' => 'First Unit',
                    'Active' => true,
                    'PayrollExportName' => null,
                    'Address' => static::ADDRESS_COMPANY,
                    'Contact' => null,
                    'RosterSortOrder' => null,
                    'ShowOnRoster' => true,
                    'Colour' => null,
                    'RosterActiveHoursSchedule' => null,
                    'DailyRosterBudget' => null,
                    'CompanyCode' => 'ADE',
                    'CompanyName' => 'First Company',
                ];
            case static::OP_UNIT_SECOND:
                return [
                    'Id' => $id,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-03-01T12:55:37+10:30',
                    'Company' => static::COMPANY_FIRST,
                    'ParentOperationalUnit' => 0,
                    'OperationalUnitName' => 'Second Unit',
                    'Active' => false,
                    'PayrollExportName' => null,
                    'Address' => static::ADDRESS_COMPANY,
                    'Contact' => null,
                    'RosterSortOrder' => null,
                    'ShowOnRoster' => true,
                    'Colour' => null,
                    'RosterActiveHoursSchedule' => null,
                    'DailyRosterBudget' => null,
                    'CompanyCode' => 'ADE',
                    'CompanyName' => 'First Company',
                ];
        }
        throw new InvalidCallException('Unknown operational unid ' . $id);
    }

    /**
     * Returns response from POST /resource/OperationalUnit/:Id endpoint
     *
     * @param integer $id Unit id
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function postResourceOperationalunit($id, $payload)
    {
        switch ($id) {
            case 'query':
                return $this->postResourceOperationalunitQuery($payload);
        }
        throw new InvalidCallException('Unknown operational unit object #' . $id);
    }

    /**
     * Returns response from POST /resource/OperationalUnit/QUERY endpoint.
     *
     * @param array $payload Query data
     *
     * @return array
     */
    protected function postResourceOperationalunitQuery($payload)
    {
        $results = [];
        $results[] = $this->getResourceOperationalunit(MockClient::OP_UNIT_FIRST);
        $results[] = $this->getResourceOperationalunit(MockClient::OP_UNIT_SECOND);
        return $results;
    }

    /**
     * Returns response to PUT /resource/OperationalUnit endpoint.
     *
     * @param array $payload PUT payload
     *
     * @return array
     *
     * @throws InvalidCallException When payload is unknown
     */
    protected function putResourceOperationalunit($payload)
    {
        $name = isset($payload['OperationalUnitName']) ? $payload['OperationalUnitName'] : null;
        switch ($name) {
            case 'New Unit':
                return $this->getResourceOperationalunit(static::OP_UNIT_NEW);
        }
        throw new InvalidCallException('Unknown operational unit payload: ' . var_export($payload, true));
    }

    /**
     * Returns response to GET /resource/Roster/:Id endpoint.
     *
     * @param integer $id Roster id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceRoster($id)
    {
        switch (strtolower($id)) {
            case 'info':
                return [
                    'fields' => [
                        'Id' => 'Integer',
                        'Date' => 'Date',
                        'StartTime' => 'Integer',
                        'EndTime' => 'Integer',
                        'Mealbreak' => 'Time',
                        'Slots' => 'VarChar',
                        'TotalTime' => 'Float',
                        'Cost' => 'Float',
                        'OperationalUnit' => 'Integer',
                        'Employee' => 'Integer',
                        'Comment' => 'VarChar',
                        'Warning' => 'VarChar',
                        'WarningOverrideComment' => 'VarChar',
                        'Published' => 'Bit',
                        'MatchedByTimesheet' => 'Integer',
                        'Open' => 'Bit',
                        'ConfirmStatus' => 'Integer',
                        'ConfirmComment' => 'VarChar',
                        'ConfirmBy' => 'Integer',
                        'ConfirmTime' => 'Integer',
                        'SwapStatus' => 'Integer',
                        'SwapManageBy' => 'Integer',
                        'ConnectStatus' => 'Integer',
                        'Creator' => 'Integer',
                        'Created' => 'DateTime',
                        'Modified' => 'DateTime',
                    ],
                    'joins' => [
                        'OperationalUnitObject' => 'OperationalUnit',
                        'EmployeeObject' => 'Employee',
                        'MatchedByTimesheetObject' => 'Timesheet',
                        'ConfirmByObject' => 'Employee',
                    ],
                    'assocs' => [],
                    'count' => 0,
                ];
            case static::ROSTER_FIRST:
                return [
                    'Id' => $id,
                    'Date' => '2017-03-20T00:00:00+10:30',
                    'StartTime' => 1489962600,
                    'EndTime' => 1489977000,
                    'Mealbreak' => '2017-03-20T00:30:00+10:30',
                    'Slots' => [
                        [
                            'blnEmptySlot' => false,
                            'strType' => 'B',
                            'intStart' => 0,
                            'intEnd' => 1800,
                            'intUnixStart' => 1651791600,
                            'intUnixEnd' => 1651793400,
                            'mixedActivity' => [
                                'intState' => 4,
                                'blnCanStartEarly' => 1,
                                'blnCanEndEarly' => 1,
                                'blnIsMandatory' => 1,
                                'strBreakType' => 'M',
                            ],
                            'strTypeName' => 'Meal Break',
                            'strState' => 'Finished Duration',
                        ],
                    ],
                    'TotalTime' => 0.0,
                    'Cost' => 0,
                    'OperationalUnit' => static::OP_UNIT_FIRST,
                    'Employee' => static::EMPLOYEE_FIRST,
                    'Comment' => '',
                    'Warning' => '',
                    'WarningOverrideComment' => '',
                    'Published' => true,
                    'MatchedByTimesheet' => 0,
                    'Open' => false,
                    'ConfirmStatus' => 0,
                    'ConfirmComment' => '',
                    'ConfirmBy' => 0,
                    'ConfirmTime' => 0,
                    'SwapStatus' => 0,
                    'SwapManageBy' => null,
                    'ConnectStatus' => null,
                    'Creator' => static::USER_ADMIN,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-02-13T11:56:23+10:30',
                    'OnCost' => 0,
                    'StartTimeLocalized' => '2017-03-20T09:00:00+10:30',
                    'EndTimeLocalized' => '2017-03-20T13:00:00+10:30',
                    'ExternalId' => null,
                    'ConnectCreator' => null,
                ];
            case static::ROSTER_NEW:
                return [
                    'Id' => $id,
                    'Date' => '2018-01-01T00:00:00+10:30',
                    'StartTime' => 1514759400,
                    'EndTime' => 1514766600,
                    'Mealbreak' => '2018-01-01T00:30:00+10:30',
                    'Slots' => [],
                    'TotalTime' => 0.0,
                    'Cost' => 0,
                    'OperationalUnit' => static::OP_UNIT_FIRST,
                    'Employee' => static::EMPLOYEE_FIRST,
                    'Comment' => '',
                    'Warning' => '',
                    'WarningOverrideComment' => '',
                    'Published' => true,
                    'MatchedByTimesheet' => 0,
                    'Open' => false,
                    'ConfirmStatus' => 0,
                    'ConfirmComment' => '',
                    'ConfirmBy' => 0,
                    'ConfirmTime' => 0,
                    'SwapStatus' => 0,
                    'SwapManageBy' => null,
                    'ConnectStatus' => null,
                    'Creator' => static::USER_FIRST,
                    'Created' => '2017-02-13T11:56:23+10:30',
                    'Modified' => '2017-02-13T11:56:23+10:30',
                    'OnCost' => 0,
                    'StartTimeLocalized' => '2018-01-01T09:00:00+10:30',
                    'EndTimeLocalized' => '2018-01-01T11:00:00+10:30',
                    'ExternalId' => null,
                    'ConnectCreator' => null,
                ];
        }
        throw new InvalidCallException('Unknown roster id ' . $id);
    }

    /**
     * Returns response from POST /resource/Roster/:Id endpoint.
     *
     * @param integer $id Roster id
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function postResourceRoster($id, $payload)
    {
        switch ($id) {
            case static::ROSTER_FIRST:
            case static::ROSTER_NEW:
                $roster = $this->getResourceRoster($id);
                $comment = isset($payload['Comment']) ? $payload['Comment'] : null;
                if ($comment) {
                    $roster = array_merge(
                        $roster,
                        [
                            'Mealbreak' => '2018-01-01T01:00:00+10:30',
                            'Open' => true,
                            'Comment' => $comment,
                        ]
                    );
                }
                return $roster;
        }
        throw new InvalidCallException('Unknown roster id ' . $id);
    }

    /**
     * Returns response from POST /supervise/roster endpoint.
     *
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When unexpected content encountered
     * @throws MockErrorException When `intStartTimestamp` is null
     */
    protected function postSuperviseRoster($payload)
    {
        $rosterId = isset($payload['intRosterId']) ? $payload['intRosterId'] : null;
        if ($rosterId == null) {
            $startTime = isset($payload['intStartTimestamp']) ? $payload['intStartTimestamp'] : null;
            if ($startTime) {
                return $this->getResourceRoster(static::ROSTER_NEW);
            } else {
                throw new MockErrorException('Manually triggered error', 500);
            }
        } elseif ($rosterId == static::ROSTER_FIRST) {
            return $this->getResourceRoster($rosterId);
        }

        throw new InvalidCallException('Unexpected roster content');
    }

    /**
     * Returns response to GET /resource/Timesheet/:Id endpoint.
     *
     * @param integer $id Timesheet id
     *
     * @return array
     *
     * @throws InvalidCallException When id is unknown
     */
    protected function getResourceTimesheet($id)
    {
        switch (strtolower($id)) {
            case 'info':
                return [
                    "fields" => [
                        "Id" => "Integer",
                        "Employee" => "Integer",
                        "EmployeeHistory" => "Integer",
                        "EmployeeAgreement" => "Integer",
                        "Date" => "Date",
                        "StartTime" => "Integer",
                        "EndTime" => "Integer",
                        "Mealbreak" => "Time",
                        "MealbreakSlots" => "VarChar",
                        "Slots" => "VarChar",
                        "TotalTime" => "Float",
                        "TotalTimeInv" => "Float",
                        "Cost" => "Float",
                        "Roster" => "Integer",
                        "EmployeeComment" => "Blob",
                        "SupervisorComment" => "VarChar",
                        "Supervisor" => "Integer",
                        "Disputed" => "Bit",
                        "TimeApproved" => "Bit",
                        "TimeApprover" => "Integer",
                        "Discarded" => "Bit",
                        "ValidationFlag" => "Integer",
                        "OperationalUnit" => "Integer",
                        "IsInProgress" => "Bit",
                        "IsLeave" => "Bit",
                        "LeaveId" => "Integer",
                        "LeaveRule" => "Integer",
                        "Invoiced" => "Bit",
                        "InvoiceComment" => "VarChar",
                        "PayRuleApproved" => "Bit",
                        "Exported" => "Bit",
                        "StagingId" => "Integer",
                        "PayStaged" => "Bit",
                        "PaycycleId" => "Integer",
                        "File" => "Integer",
                        "CustomFieldData" => "Integer",
                        "RealTime" => "Bit",
                        "AutoProcessed" => "Bit",
                        "AutoRounded" => "Bit",
                        "AutoTimeApproved" => "Bit",
                        "AutoPayRuleApproved" => "Bit",
                        "Creator" => "Integer",
                        "Created" => "DateTime",
                        "Modified" => "DateTime",
                    ],
                    "joins" => [
                        "EmployeeObject" => "Employee",
                        "EmployeeAgreementObject" => "EmployeeAgreement",
                        "RosterObject" => "Roster",
                        "OperationalUnitObject" => "OperationalUnit",
                        "Leave" => "Leave",
                        "LeaveRuleObject" => "LeaveRules",
                        "Paycycle" => "EmployeePaycycle",
                        "CustomFieldDataObject" => "CustomFieldData",
                    ],
                    "assocs" => [],
                    "count" => 0,
                ];
            case static::TIMESHEET_NEW:
                return [
                    'Id' => $id,
                    'Employee' => static::EMPLOYEE_FIRST,
                    'EmployeeHistory' => 123,
                    'EmployeeAgreement' => 123,
                    'Date' => '2018-01-01T00:00:00+10:30',
                    'StartTime' => 1514759400,
                    'EndTime' => 1514766600,
                    'Mealbreak' => '2018-01-01T00:30:00+10:30',
                    'MealbreakSlots' => '',
                    'Slots' => [],
                    'TotalTime' => 1.5,
                    'TotalTimeInv' => 1.5,
                    'Cost' => null,
                    'Roster' => null,
                    'EmployeeComment' => '.',
                    'SupervisorComment' => null,
                    'Supervisor' => null,
                    'Disputed' => false,
                    'TimeApproved' => false,
                    'TimeApprover' => null,
                    'Discarded' => null,
                    'ValidationFlag' => 0,
                    'OperationalUnit' => static::OP_UNIT_FIRST,
                    'IsInProgress' => false,
                    'IsLeave' => false,
                    'LeaveId' => null,
                    'LeaveRule' => null,
                    'Invoiced' => false,
                    'InvoiceComment' => null,
                    'PayRuleApproved' => false,
                    'Exported' => null,
                    'StagingId' => null,
                    'PayStaged' => false,
                    'PaycycleId' => 2124,
                    'File' => null,
                    'CustomFieldData' => null,
                    'RealTime' => 0,
                    'AutoProcessed' => 0,
                    'AutoRounded' => 0,
                    'AutoTimeApproved' => 0,
                    'AutoPayRuleApproved' => 0,
                    'Creator' => static::EMPLOYEE_FIRST,
                    'Created' => '2018-01-01T00:00:00+10:30',
                    'Modified' => '2018-01-01T00:00:00+10:30',
                    'OperationalUnitObject' => $this->getResourceOperationalunit(static::OP_UNIT_FIRST),
                    'OnCost' => 0,
                    'StartTimeLocalized' => '2018-01-01T09:00:00+10:30',
                    'EndTimeLocalized' => '2018-01-01T11:00:00+10:30',
                    'CanEdit' => true,
                ];
            case static::TIMESHEET_WITH_SLOTS:
                // Sourced from: https://developer.deputy.com/deputy-docs/docs/retrieving-timesheets-from-deputy
                return [
                    'Id' => $id,
                    'Employee' => 3,
                    'EmployeeHistory' => 2586,
                    'EmployeeAgreement' => 5,
                    'Date' => '2022-05-06T00:00:00+10:00',
                    'StartTime' => 1651791600,
                    'EndTime' => 1651834800,
                    'Mealbreak' => '2022-05-06T00:30:00+10:00',
                    'MealbreakSlots' => [
                        '1651829880' => 'OUT',
                        '1651832340' => 'IN',
                    ],
                    'Slots' => [
                        [
                            'blnEmptySlot' => false,
                            'strType' => 'B',
                            'intStart' => 0,
                            'intEnd' => 1800,
                            'intUnixStart' => 1651791600,
                            'intUnixEnd' => 1651793400,
                            'mixedActivity' => [
                                'intState' => 4,
                                'blnCanStartEarly' => 1,
                                'blnCanEndEarly' => 1,
                                'blnIsMandatory' => 1,
                                'strBreakType' => 'M',
                            ],
                            'strTypeName' => 'Meal Break',
                            'strState' => 'Finished Duration',
                        ],
                    ],
                    'TotalTime' => 11.5,
                    'TotalTimeInv' => 4.48,
                    'Cost' => 345,
                    'Roster' => 15,
                    'EmployeeComment' => null,
                    'SupervisorComment' => null,
                    'Supervisor' => null,
                    'Disputed' => false,
                    'TimeApproved' => true,
                    'TimeApprover' => 3,
                    'Discarded' => null,
                    'ValidationFlag' => 0,
                    'OperationalUnit' => 1,
                    'IsInProgress' => false,
                    'IsLeave' => false,
                    'LeaveId' => null,
                    'LeaveRule' => null,
                    'Invoiced' => false,
                    'InvoiceComment' => null,
                    'PayRuleApproved' => true,
                    'Exported' => null,
                    'StagingId' => 1,
                    'PayStaged' => true,
                    'PaycycleId' => 1,
                    'File' => null,
                    'CustomFieldData' => null,
                    'RealTime' => true,
                    'AutoProcessed' => true,
                    'AutoRounded' => false,
                    'AutoTimeApproved' => false,
                    'AutoPayRuleApproved' => true,
                    'Creator' => 3,
                    'Created' => '2022-05-06T16:31:29+10:00',
                    'Modified' => '2022-05-07T14:11:41+10:00',
                    'OnCost' => 345,
                    'StartTimeLocalized' => '2022-05-06T09:00:00+10:00',
                    'EndTimeLocalized' => '2022-05-06T21:00:00+10:00',
                    '_DPMetaData' => [
                        'System' => 'Timesheet',
                        'CreatorInfo' => [
                            'Id' => 3,
                            'DisplayName' => 'AdamRhys Sapo',
                            'EmployeeProfile' => 3,
                            'Employee' => 3,
                            'Photo' => 'https://photo2.deputy.com/deputec_b220505063318_11516/-135x135_45ca58e0db1a271fda5c83c399f7221a.jpg?Expires=1662765191&Key-Pair-Id=APKAINP5UVPK4IGBHXOQ&Signature=NciTc-O2aFxvvE23YrP3XkGqqPAtXMJSbAoZ8NQNENtKVTt1hXPHmGiW~Y59KRhpUbjFH08iIybrmH5f8NeSXn0q5RSMdXo0c2KyyYUoCecX97pa1eOqLTEmc6kaPJhiDLXt86~-4BaNvpK20eVa0WZkk4TQL5SgPi7w3skT7jE_',
                            'Pronouns' => 4,
                            'CustomPronouns' => 'Carlos',
                        ],
                        'OperationalUnitInfo' => [
                            'Id' => 1,
                            'OperationalUnitName' => 'Chef',
                            'Company' => 1,
                            'CompanyName' => 'Simons Sambos',
                            'LabelWithCompany' => '[Sim] Chef',
                        ],
                        'EmployeeInfo' => [
                            'Id' => 3,
                            'DisplayName' => 'AdamRhys Sapo',
                            'EmployeeProfile' => 3,
                            'Employee' => 3,
                            'Photo' => 'https://photo2.deputy.com/deputec_b220505063318_11516/-135x135_45ca58e0db1a271fda5c83c399f7221a.jpg?Expires=1662765191&Key-Pair-Id=APKAINP5UVPK4IGBHXOQ&Signature=NciTc-O2aFxvvE23YrP3XkGqqPAtXMJSbAoZ8NQNENtKVTt1hXPHmGiW~Y59KRhpUbjFH08iIybrmH5f8NeSXn0q5RSMdXo0c2KyyYUoCecX97pa1eOqLTEmc6kaPJhiDLXt86~-4BaNvpK20eVa0WZkk4TQL5SgPi7w3skT7jE_',
                            'Pronouns' => 4,
                            'CustomPronouns' => 'Carlos',
                        ],
                        'CustomFieldData' => null,
                    ],
                ];
        }
        throw new InvalidCallException('Unknown timesheet id ' . $id);
    }

    /**
     * Returns response from POST /supervise/timesheet endpoint.
     *
     * @param array $payload POST data
     *
     * @return array
     *
     * @throws InvalidCallException When unexpected content encountered
     * @throws MockErrorException When `intStartTimestamp` is null
     */
    protected function postSuperviseTimesheetUpdate($payload)
    {
        $timesheetId = isset($payload['intTimesheetId']) ? $payload['intTimesheetId'] : null;
        if ($timesheetId == null) {
            $startTime = isset($payload['intStartTimestamp']) ? $payload['intStartTimestamp'] : null;
            if ($startTime) {
                return $this->getResourceTimesheet(static::TIMESHEET_NEW);
            } else {
                throw new MockErrorException('Manually triggered error', 500);
            }
        }

        throw new InvalidCallException('Unexpected timesheet content');
    }

    /**
     * Returns the access token details.
     *
     * @param string $token
     * @param array $payload
     *
     * @return array
     */
    protected function getOAuthAccessToken($token, $payload)
    {
        switch ($token) {
            case static::OAUTH_TOKEN_ACTIVE:
                return [
                    'access_token' => static::OAUTH_TOKEN_ACTIVE,
                    'expires_in' => 86400,
                    'scope' => $payload['scope'],
                    'endpoint' => $this->getWrapper()->target->getOAuth2EndPoint(),
                    'refresh_token' => static::OAUTH_REFRESH_EXPIRED,
                ];
            case static::OAUTH_TOKEN_EXPIRED:
                return [
                    'access_token' => static::OAUTH_TOKEN_EXPIRED,
                    'expires_in' => -100, // Force the updated token to instantly expire
                    'scope' => $payload['scope'],
                    'endpoint' => $this->getWrapper()->target->getOAuth2EndPoint(),
                    'refresh_token' => static::OAUTH_REFRESH_ACTIVE,
                ];
        }
    }

    /**
     * Handles the `authorization_code` OAuth2 process.
     *
     * @param array $payload
     *
     * @return array
     *
     * @throws InvalidCallException When the payload is not as expected
     */
    protected function oauthAuthorizationCode($payload)
    {
        switch ($payload['code']) {
            case static::OAUTH_CODE_ACTIVE:
                return $this->getOAuthAccessToken(static::OAUTH_TOKEN_ACTIVE, $payload);
        }

        throw new InvalidCallException('Unexpected authorization_code content');
    }

    /**
     * Handles the `refresh_token` OAuth2 process.
     *
     * @param array $payload
     *
     * @return array
     *
     * @throws InvalidCallException When the payload is not as expected
     */
    protected function oauthRefreshToken($payload)
    {
        switch ($payload['refresh_token']) {
            case static::OAUTH_REFRESH_EXPIRED:
                return $this->getOAuthAccessToken(static::OAUTH_TOKEN_EXPIRED, $payload);
            case static::OAUTH_REFRESH_ACTIVE:
                return $this->getOAuthAccessToken(static::OAUTH_TOKEN_ACTIVE, $payload);
        }

        throw new InvalidCallException('Unexpected refresh_token content');
    }
}
