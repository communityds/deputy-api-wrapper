<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $country
 * @property string $code
 * @property boolean $active
 * @property integer $sortOrder
 * @property string $state
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
