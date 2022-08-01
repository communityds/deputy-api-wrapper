<?php

namespace CommunityDS\Deputy\Api\Helper;

use DateTime;

/**
 * Provides helper functions when dealing with DateTime instances.
 */
abstract class DateTimeHelper
{
    /**
     * Returns a DateTime instance corresponding to midnight within the
     * same time zone.
     *
     * @param DateTime $dateTime Date time instance
     *
     * @return DateTime
     */
    public static function getMidnight($dateTime)
    {
        return new DateTime($dateTime->format('Y-m-d 00:00:00'), $dateTime->getTimezone());
    }

    /**
     * Returns the number of seconds from a date time object relative to
     * midnight within the same time zone.
     *
     * @param DateTime $dateTime Date time instance
     *
     * @return integer Whole number of seconds
     */
    public static function getSecondsFromMidnight($dateTime)
    {
        return floor($dateTime->getTimestamp() - static::getMidnight($dateTime)->getTimestamp());
    }

    /**
     * Returns the number of minutes from a date time object relative to
     * midnight within the same time zone.
     *
     * @param DateTime $dateTime Date time instance
     * @param boolean $floor True to perform floor rounding; false to ceiling rounding
     *
     * @return integer
     */
    public static function getMinutesFromMidnight($dateTime, $floor = true)
    {
        $seconds = static::getSecondsFromMidnight($dateTime);
        if ($floor) {
            return floor($seconds / 60);
        }
        return ceil($seconds / 60);
    }
}
