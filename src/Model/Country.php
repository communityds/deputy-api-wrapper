<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property string $code
 * @property string $codeA3
 * @property string $country
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property string $phoneDisplayPreg
 * @property string $region
 * @property integer $sortOrder
 * @property string $zipValidatePreg
 *
 * @property string $displayName
 */
class Country extends Record
{
    /**
     * Returns the country name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->country;
    }
}
