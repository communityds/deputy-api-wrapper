<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $fax
 * @property string $phone1Type
 * @property string $phone2Type
 * @property string $phone3Type
 * @property integer $primaryPhone
 * @property string $email1
 * @property string $email1Type
 * @property string $email2Type
 * @property string $email2
 * @property integer $primaryEmail
 * @property string $im1
 * @property string $im2
 * @property string $im1Type
 * @property string $im2Type
 * @property string $web
 * @property string $notes
 * @property boolean $saved
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class Contact extends Record
{
}
