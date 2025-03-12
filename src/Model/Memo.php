<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\InvalidParamException;
use CommunityDS\Deputy\Api\NotSupportedException;
use DateTime;

/**
 * @property boolean $active
 * @property string $confirmText
 * @property mixed $content
 * @property DateTime $created
 * @property integer $creator
 * @property boolean $disableComment
 * @property integer $file
 * @property mixed $keyword
 * @property DateTime $modified
 * @property DateTime $showFrom
 * @property DateTime $showTill
 * @property string $title
 * @property integer $type
 * @property string $url
 *
 * @property Company $company
 * @property EmployeeRole $role
 * @property Team $team
 *
 * @property string $displayName
 */
class Memo extends Record
{
    /**
     * @var integer[] User recipients of this Memo
     */
    private $assignedUserIds = [];

    /**
     * @var integer[] Company recipients of this Memo
     */
    private $assignedCompanyIds = [];

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->title;
    }

    /**
     * Creates new Memo resource via the POST /supervise/memo endpoint,
     * and then sends any remaining attributes to POST /resource/Memo/:id endpoint.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use only populated attributes.
     *
     * @return boolean
     *
     * @throws InvalidParamException When current instance already has a primary key
     */
    public function insert($attributeNames = null)
    {
        return $this->postSuperviseMemo(
            $this->insertPayload($attributeNames)
        );
    }

    /**
     * Sends a payload to the POST /supervise/memo endpoint
     * and then sends remaining payload to POST /resource/Memo/:id endpoint.
     *
     * @param array $original Original payload
     *
     * @return boolean
     *
     * @throws NotSupportedException When attempting to update existing record
     */
    protected function postSuperviseMemo($original)
    {
        $payload = [];
        if ($this->getPrimaryKey()) {
            throw new NotSupportedException('postSuperviseMemo does not support updating Memo');
        } else {
            $payload['arrAssignedCompanyIds']   = $this->assignedCompanyIds;
            $payload['arrAssignedUserIds']      = $this->assignedUserIds;
        }

        foreach ($original as $name => $value) {
            switch ($name) {
                case 'Content':
                    $payload['strContent'] = $value;
                    unset($original[$name]);
                    break;
                case 'Company':
                    $payload['intCompany'] = $value;
                    unset($original[$name]);
                    break;
                case 'File':
                    $payload['arrFileIds'] = [$value];
                    unset($original[$name]);
                    break;
            }
        }

        $response = $this->getWrapper()->client->post(
            'supervise/memo',
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        // Determine additional fields that the supervise/memo endpoint
        // does not support and if any are found then change their value
        // within the model and force an update.

        $updateRecord = false;
        foreach ($original as $key => $value) {
            if (array_key_exists($key, $response)) {
                if ($response[$key] != $value) {
                    $this->{$key} = $value;
                    $updateRecord = true;
                }
            } else {
                $this->{$key} = $value;
                $updateRecord = true;
            }
        }

        if ($updateRecord) {
            return parent::update();
        }

        return true;
    }

    /**
     * Adds userId that a new Memo should go to
     *
     * @param integer $userId
     *
     * @throws NotSupportedException Only available on new Memo
     */
    public function addAssignedUserId($userId)
    {
        if ($this->getPrimaryKey()) {
            throw new NotSupportedException('Adding recipient User only available on new Memo');
        }

        $this->assignedUserIds[] = $userId;
    }

    /**
     * Adds Location/companyId that a new Memo should go to
     *
     * @param integer $companyId
     *
     * @throws NotSupportedException Only available on new Memo
     */
    public function addAssignedCompanyId($companyId)
    {
        if ($this->getPrimaryKey()) {
            throw new NotSupportedException('Adding recipient Company only available on new Memo');
        }

        $this->assignedCompanyIds[] = $companyId;
    }
}
