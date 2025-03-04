<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property mixed $comment
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property string $provider
 * @property integer $providerAddress
 * @property integer $renewalPeriodMonths
 * @property integer $timeRequiredDays
 * @property string $title
 *
 * @property OperationalUnit $trainingModule
 *
 * @property string $displayName
 */
class TrainingModule extends Record
{
    /**
     * Returns module title.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->title;
    }
}
