<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property float $addToBudget
 * @property boolean $blockTimeOff
 * @property string $colour
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property integer $schedule
 * @property boolean $showOnRoster
 * @property mixed $title
 *
 * @property Schedule $scheduleObject
 *
 * @property OperationalUnit $operationalUnit
 *
 * @property string $displayName
 */
class Event extends Record
{
    /**
     * Returns the event title.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->title;
    }
}
