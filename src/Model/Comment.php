<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property mixed $comment
 * @property DateTime $created
 * @property integer $creator
 * @property integer $file
 * @property boolean $ignorePermission
 * @property boolean $inFeed
 * @property DateTime $modified
 * @property string $orm
 * @property integer $recId
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
