<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property DateTime $date
 * @property DateTime $timestamp
 * @property integer $employee
 * @property integer $operationalUnit
 * @property string $salesType
 * @property string $salesRef
 * @property float $salesQty
 * @property float $salesAmount
 * @property string $salesPayload
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property OperationalUnit $operationalUnitObject
 */
class SalesData extends Record
{

}
