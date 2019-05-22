<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $orm
 * @property integer $recId
 * @property boolean $inFeed
 * @property boolean $ignorePermission
 * @property string $comment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Category $commentObject
 */
class Comment extends Record
{

    /**
     * Returns the `comment` association as there is a name conflict
     * with the `comment` property.
     *
     * @return Category
     */
    public function getCommentObject()
    {
        return $this->getRelation('Comment');
    }

    /**
     * Returns the comment content.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->comment;
    }
}
