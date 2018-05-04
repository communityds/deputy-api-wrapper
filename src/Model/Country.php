<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $code
 * @property string $codeA3
 * @property string $region
 * @property boolean $active
 * @property integer $sortOrder
 * @property string $country
 * @property string $zipValidatePreg
 * @property string $phoneDisplayPreg
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
