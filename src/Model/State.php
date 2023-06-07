<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property string $code
 * @property integer $country
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property integer $sortOrder
 * @property string $state
 *
 * @property Country $countryObject
 *
 * @property string $displayName
 */
class State extends Record
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->state;
    }
}
