<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $title
 * @property string $provider
 * @property integer $providerAddress
 * @property float $cost
 * @property integer $timeRequiredDays
 * @property integer $renewalPeriodMonths
 * @property string $comment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
