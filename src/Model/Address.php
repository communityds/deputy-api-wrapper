<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $contactName
 * @property string $unitNo
 * @property string $streetNo
 * @property string $suiteNo
 * @property string $poBox
 * @property string $street1
 * @property string $street2
 * @property string $city
 * @property string $state
 * @property string $postcode
 * @property integer $country
 * @property string $phone
 * @property string $notes
 * @property integer $format
 * @property boolean $saved
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property string $displayName
 *
 * @property Country $countryObject
 * @property State $stateObject
 */
class Address extends Record
{

    /**
     * Returns the details of the address on one line.
     *
     * @return string
     */
    public function getDisplayName()
    {
        $lines = [];
        if ($this->street1) {
            $lines[] = $this->street1;
        }
        if ($this->street2) {
            $lines[] = $this->street2;
        }
        if ($this->city || $this->state || $this->postcode) {
            $lines[] = trim($this->city . ' ' . $this->state . ' ' . $this->postcode);
        }
        return implode(', ', $lines);
    }

    /**
     * Returns country object instance linked to this address.
     *
     * @return Country|null
     */
    public function getCountryObject()
    {
        return $this->getWrapper()->getCountry($this->country);
    }

    /**
     * Returns state object instance linked to this address.
     *
     * @return State|null
     */
    public function getStateObject()
    {
        if (is_numeric($this->state)) {
            return $this->getWrapper()->getState($this->state);
        }
        return null;
    }
}
