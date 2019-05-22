<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Schema\ResourceInfo;

/**
 * Interface for any class that can be used to store and manipulate
 * data within the API.
 */
interface ModelInterface
{

    /**
     * Returns the id of the resource.
     *
     * @return string
     */
    public function getPrimaryKey();

    /**
     * Returns the name of the API resource this model represents.
     *
     * @return string
     */
    public function getResourceName();

    /**
     * Returns the schema related to this resource.
     *
     * @return ResourceInfo|null
     */
    public function getSchema();

    /**
     * Creates a new instance of this class.
     *
     * @param array $data Data set
     * @param ResourceInfo $schema Resource schema
     *
     * @return static
     */
    public static function instantiate($data, $schema);

    /**
     * Populates a record from the values received by the API.
     *
     * @param static $record Record being populated
     * @param array $data Data from the API
     *
     * @return void
     */
    public static function populateRecord($record, $data);

    /**
     * Updates the details of the resource that this record is maintained under.
     *
     * @param string $resourceName Resource name of the parent resource
     * @param integer $primaryKey Id of the parent resource
     * @param string $foreignName Name of the relation in the parent resource
     *                            that references this resource
     *
     * @return void
     */
    public function setParentResource($resourceName, $primaryKey, $foreignName);
}
