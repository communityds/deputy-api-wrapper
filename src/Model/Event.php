<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $title
 * @property integer $schedule
 * @property string $colour
 * @property boolean $showOnRoster
 * @property float $addToBudget
 * @property boolean $blockTimeOff
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
