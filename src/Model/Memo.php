<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property DateTime $showFrom
 * @property boolean $active
 * @property DateTime $showTill
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $file
 * @property string $url
 * @property string $confirmText
 * @property string $keyword
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
     * Returns the title.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->title;
    }
}
