<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property integer $employee
 * @property DateTime $modified
 * @property integer $operationalUnit
 * @property float $salesAmount
 * @property mixed $salesPayload
 * @property float $salesQty
 * @property string $salesRef
 * @property string $salesType
 * @property DateTime $timestamp
 *
 * @property Employee $employeeObject
 * @property OperationalUnit $operationalUnitObject
 */
class SalesData extends Record
{
}
