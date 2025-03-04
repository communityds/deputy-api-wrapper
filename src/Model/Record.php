<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\DeputyException;
use CommunityDS\Deputy\Api\Helper\ClientHelper;
use CommunityDS\Deputy\Api\InvalidParamException;
use CommunityDS\Deputy\Api\NotSupportedException;
use CommunityDS\Deputy\Api\Schema\ResourceInfo;
use CommunityDS\Deputy\Api\Schema\UnknownDataTypeException;
use CommunityDS\Deputy\Api\Schema\UnknownFieldException;
use CommunityDS\Deputy\Api\Schema\UnknownRelationException;
use CommunityDS\Deputy\Api\WrapperLocatorTrait;

/**
 * Foundation for any classes that model a Resource within the API.
 *
 * @property integer $id
 * @property string $resourceName
 *
 * @property ResourceInfo $schema
 */
abstract class Record extends Component implements ModelInterface
{
    use WrapperLocatorTrait;

    /**
     * Data within this model.
     *
     * @var array
     */
    private $_data = [];

    /**
     * Errors within this record.
     *
     * @var array
     */
    private $_errors = null;

    /**
     * Older data within this model.
     *
     * @var array
     */
    private $_old = [];

    /**
     * Related object data.
     *
     * @var array
     */
    private $_related = [];

    /**
     * Indicates if a relationship has been populated.
     *
     * @var boolean
     */
    private $_populated = [];

    /**
     * Name of the API Resource to derive schema information from.
     *
     * @var string
     */
    private $_resourceName;

    /**
     * Name of the API Resource this record is located under.
     *
     * @var string
     */
    private $_parentResourceName;

    /**
     * Primary key of the API Resource this record is located under.
     *
     * @var integer
     */
    private $_parentResourceKey;

    /**
     * Name of the relation relative to the parent record.
     *
     * @var string
     */
    private $_parentForeignName;

    public function init()
    {
        parent::init();
        if ($this->_resourceName === null) {
            throw new InvalidParamException('Resource name must be provided when creating a model');
        }
    }

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (InvalidParamException $e) {
            // Check attributes and relations when not part of class
        }

        try {
            return $this->getAttribute($name);
        } catch (UnknownFieldException $e) {
            // skip unknown attributes
        }

        try {
            return $this->getRelation($name);
        } catch (UnknownRelationException $ee) {
            // skip unknown relationships
        } catch (\Exception $ee) {
            // pass through unknown exceptions
            throw $ee;
        }

        throw $e;
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
            return;
        } catch (InvalidParamException $e) {
            // Assume value is for an attribute if not part of class
        }

        $this->setAttribute($name, $value);
    }

    /**
     * Returns the errors identified within this record.
     *
     * @param string $attribute Attribute to return errors for; or null
     *                          for to return errors for all fields
     *
     * @return array If no attribute provided then two dimensional array
     *               where key as field name and value as error messages;
     *               if attribute provided then a one dimensional array
     *               of errors messages
     */
    public function getErrors($attribute = null)
    {
        if ($this->_errors !== null) {
            if ($attribute === null) {
                return $this->_errors;
            }

            $fieldName = $this->schema->fieldName($attribute);
            $key = $this->schema->attributeName($fieldName);
            if (array_key_exists($key, $this->_errors)) {
                return $this->_errors[$key];
            }
        }

        return [];
    }

    /**
     * Adds an error for a specific attribute.
     *
     * @param string $attribute Attribute name
     * @param string $message Error message to add
     *
     * @return $this
     */
    public function addError($attribute, $message)
    {
        $fieldName = $this->schema->fieldName($attribute);
        $key = $this->schema->attributeName($fieldName);
        if ($this->_errors === null) {
            $this->_errors = [];
        }
        if (!array_key_exists($key, $this->_errors)) {
            $this->_errors[$key] = [];
        }
        $this->_errors[$key][] = $message;
        return $this;
    }

    /**
     * Empties the record of all data.
     *
     * @return void
     */
    protected function clearData()
    {
        $this->_data = [];
        $this->_errors = null;
        $this->_old = [];
        $this->_populated = [];
        $this->_related = [];
    }

    /**
     * Returns the name of the key within the data.
     *
     * @param string $name Property name
     *
     * @return string Key name
     */
    protected function dataKey($name)
    {
        return strtolower($name);
    }

    public function getPrimaryKey()
    {
        return $this->id;
    }

    public function getResourceName()
    {
        return $this->_resourceName;
    }

    /**
     * Sets the resource name.
     *
     * @param string $name Resource name.
     *
     * @return void
     */
    public function setResourceName($name)
    {
        $this->_resourceName = $name;
    }

    public function getSchema()
    {
        return $this->getWrapper()->schema->resource($this->getResourceName());
    }

    /**
     * Indicates if this resource has an attribute/field.
     *
     * @param string $name Attribute name
     *
     * @return boolean
     */
    public function hasAttribute($name)
    {
        return $this->getSchema()->fieldName($name) != null;
    }

    /**
     * Returns the value of a specific attribute/field.
     *
     * @param string $name Attribute name
     *
     * @return mixed Attribute value
     *
     * @throws UnknownFieldException When attribute is unknown
     */
    public function getAttribute($name)
    {
        $dataKey = $this->dataKey($name);
        if (array_key_exists($dataKey, $this->_data)) {
            return $this->_data[$dataKey];
        }

        // Default value for fields
        $fieldName = $this->getSchema()->fieldName($name);
        if ($fieldName) {
            return null;
        }

        throw new UnknownFieldException('Unknown attribute ' . $name);
    }

    /**
     * Sets the value of a specific attribute/field.
     *
     * @param string $name Attribute name
     * @param string $value Attribute value
     *
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        $this->setAttributes([$name => $value]);
        return $this;
    }

    /**
     * Sets the values of multiple attributes/fields.
     *
     * @param array $values Attribute names as keys; values as attribute values
     *
     * @return $this
     *
     * @throws UnknownFieldException When any attribute is unknown
     */
    public function setAttributes($values)
    {
        foreach ($values as $name => $value) {
            $fieldName = $this->getSchema()->fieldName($name);
            if ($fieldName) {
                $dataKey = $this->dataKey($name);
                if (!array_key_exists($dataKey, $this->_old)) {
                    $this->_old[$dataKey] = isset($this->_data[$dataKey]) ? $this->_data[$dataKey] : null;
                }
                $this->_data[$dataKey] = $value;
            } else {
                throw new UnknownFieldException('Unknown attribute ' . $name);
            }
        }
        return $this;
    }

    /**
     * Returns the older value of an attribute, or null if no value is present.
     *
     * @param string $name Attribute name
     *
     * @return mixed|null Attribute value; or null if no older value
     */
    public function getOldAttribute($name)
    {
        $dataKey = $this->dataKey($name);
        if (array_key_exists($dataKey, $this->_old)) {
            return $this->_old[$dataKey];
        }
        return null;
    }

    /**
     * Updates the older values of attributes.
     *
     * @param array $values Attribute values where key is attribute name
     *
     * @return void
     */
    public function setOldAttributes($values)
    {
        $this->_old = [];
        foreach ($values as $key => $value) {
            $this->_old[$this->dataKey($key)] = $value;
        }
    }

    /**
     * Indicates if an attribute is considered to be dirty as their original
     * value has changed.
     *
     * @param string $name Attribute name
     *
     * @return boolean
     */
    public function isAttributeDirty($name)
    {
        if (array_key_exists($this->dataKey($name), $this->_old)) {
            return true;
        }
        return false;
    }

    /**
     * Returns the current values of attributes that are considered to be dirty
     * as their original value has changed.
     *
     * @return array Key as attribute name; value as attribute value
     */
    public function getDirtyAttributes()
    {
        $values = [];
        foreach ($this->_old as $key => $value) {
            $fieldName = $this->getSchema()->fieldName($key);
            $values[$fieldName] = isset($this->_data[$key]) ? $this->_data[$key] : null;
        }
        return $values;
    }

    /**
     * Indicates if this resource has a specific join or association.
     *
     * @param string $relation Relation name
     *
     * @return boolean
     */
    public function hasRelation($relation)
    {
        return $this->getSchema()->relationName($relation) != null;
    }

    /**
     * Returns the value of a specific relation.
     *
     * @param string $relation Relation name
     *
     * @return static[]
     *
     * @throws UnknownRelationException When relation is unknown
     */
    public function getRelation($relation)
    {
        $relationName = $this->getSchema()->relationName($relation);
        if ($relationName == null) {
            throw UnknownRelationException::create($this, $relation);
        }

        $dataKey = $this->dataKey($relationName);
        if (!array_key_exists($dataKey, $this->_populated)) {
            $resourceName = $this->getSchema()->relationResource($relationName);
            $resourceSchema = $this->getWrapper()->schema->resource($resourceName);
            if ($resourceSchema) {
                $query = $resourceSchema->find();
                $query->primaryModel = $this;
                $query->foreignName = $relationName;
                if (array_key_exists($dataKey, $this->_related)) {
                    $query->data = $this->_related[$dataKey];
                    if (!is_array($query->data)) {
                        $query->data = [$query->data];
                    }
                }
                $this->_related[$dataKey] = $query->one();
            } else {
                $this->_related[$dataKey] = null;
            }
            $this->_populated[$dataKey] = true;
        }

        return $this->_related[$dataKey];
    }

    /**
     * Populates value within a relationship.
     *
     * @param string $relation Relation name
     * @param array $data Data from API
     *
     * @return void
     *
     * @throws UnknownRelationException When relation is unknown
     */
    public function populateRelation($relation, $data)
    {
        $name = $this->getSchema()->relationName($relation);
        if ($name == null) {
            throw UnknownRelationException::create($this, $relation);
        }
        $key = $this->dataKey($name);
        $this->_related[$key] = $data;
        unset($this->_populated[$key]);
    }

    /**
     * Indicates if a relationship has been populated.
     *
     * @param string $relation Relation name
     *
     * @return boolean
     */
    public function isRelationPopulated($relation)
    {
        $key = $this->dataKey($this->getSchema()->relationName($relation));
        if ($key && array_key_exists($key, $this->_populated)) {
            return $this->_populated[$key] == true;
        }
        return false;
    }

    /**
     * Sends changes to the model to the Deputy API. If the Id is null then
     * a new record will be created, otherwise an existing record will be updated.
     *
     * @param string[] $attributeNames Names of attribute to save; or null to use
     *                                 only dirty attributes
     *
     * @return boolean True if saved successfully; false otherwise
     */
    public function save($attributeNames = null)
    {
        if ($this->getPrimaryKey() == null) {
            return $this->insert($attributeNames);
        }
        return $this->update($attributeNames);
    }

    /**
     * Creates a new record by sending a request to the Deputy API.
     * It is an expectation of this method that all details be updated from the API
     * when a record is successfully created.
     *
     * If records should be created using one of the other Deputy API calls
     * (e.g. Management Calls or Timesheet Calls) then overload this method
     * within the resource model.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return boolean True if created successfully; false otherwise
     *
     * @throws NotSupportedException When there is no API route to create resource
     */
    public function insert($attributeNames = null)
    {
        $payload = $this->insertPayload($attributeNames);

        $route = $this->schema->route();
        if ($route == null) {
            throw new NotSupportedException(
                'API does not support creation of ' . $this->schema->name . ' resources'
            );
        }

        $response = $this->getWrapper()->client->put(
            $route,
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        return true;
    }

    /**
     * Returns the payload expected to be sent to the API endpoint when creating
     * a new resource.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return array
     *
     * @throws InvalidParamException If there is a primary key (id)
     */
    protected function insertPayload($attributeNames)
    {
        if ($this->getPrimaryKey()) {
            throw new InvalidParamException('New records can not contain an Id');
        }

        if ($attributeNames === null) {
            $attributeNames = array_keys($this->_data);
        }

        $payload = [];
        $schema = $this->getSchema();
        foreach ($attributeNames as $name) {
            $fieldName = $schema->fieldName($name);
            if ($fieldName) {
                $dataType = $schema->fieldDataType($name);
                if ($dataType) {
                    $payload[$fieldName] = $dataType->toApi($this->getAttribute($name));
                }
            }
        }

        return $payload;
    }

    /**
     * Updates an existing record by sending a request to the Deputy API.
     * Only fields that have been changed will be sent to the API.
     * It is an expectation of this method that all details be updated from
     * the API when a record is successfully updated.
     *
     * If records should be updated by using one of the other Deputy API calls
     * (e.g. Management Calls or Timesheet Calls) then overload this method
     * within the resource model.
     *
     * @param string[] $attributeNames Names of attributes to update; or null to use
     *                                 only dirty attributes.
     *
     * @return boolean True if created successfully; false otherwise
     *
     * @throws NotSupportedException When there is no API route to update resource
     */
    public function update($attributeNames = null)
    {
        $payload = $this->updatePayload($attributeNames);

        $route = null;
        if ($this->_parentResourceName) {
            $schema = $this->getWrapper()->schema->resource($this->_parentResourceName);
            if ($schema) {
                $route = $schema->route(
                    $this->_parentResourceKey,
                    $this->_parentForeignName
                );
            }
        } else {
            $route = $this->schema->route($this->getPrimaryKey());
        }
        if ($route == null) {
            throw new NotSupportedException(
                'API does not support updating ' . $this->schema->name . ' resources' .
                ($this->_parentForeignName ?
                    (' via ' . $this->_parentForeignName .
                        '/' . $this->_parentResourceKey .
                        '/' . $this->_parentForeignName) :
                    '')
            );
        }

        $response = $this->getWrapper()->client->post(
            $route,
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        return true;
    }

    /**
     * Returns the payload expected to be sent to the API endpoint when updating
     * an existing resource.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return array
     *
     * @throws InvalidParamException If there is not a primary key (id)
     */
    protected function updatePayload($attributeNames)
    {
        if ($this->getPrimaryKey() == null) {
            throw new InvalidParamException('Existing records must contain an Id');
        }

        if ($attributeNames === null) {
            $attributeNames = array_keys($this->getDirtyAttributes());
        }

        $payload = [];
        $schema = $this->getSchema();
        foreach ($attributeNames as $name) {
            $fieldName = $schema->fieldName($name);
            if ($fieldName) {
                $dataType = $schema->fieldDataType($name);
                if ($dataType) {
                    $payload[$fieldName] = $dataType->toApi($this->getAttribute($name));
                }
            }
        }

        return $payload;
    }

    /**
     * Processes the error from the client and updates the internal error messages.
     *
     * @param array $errors Error from the client
     *
     * @return void
     */
    protected function setErrorsFromResponse($errors)
    {
        try {
            ClientHelper::checkResponse($errors);
        } catch (DeputyException $e) {
            $this->addError('id', $e->getMessage());
        }
    }

    /**
     * Deletes an existing record by sending a request to the Deputy API.
     *
     * If records should be deleted by using one of the other Deputy API calls
     * (e.g. Management Calls or Timesheet Calls) then overload this method
     * within the resource model.
     *
     * @return boolean True if deleted; false otherwise
     *
     * @throws InvalidParamException When the record does not contain an id
     * @throws NotSupportedException When there is no API route to delete resource
     */
    public function delete()
    {
        if ($this->getPrimaryKey() == null) {
            throw new InvalidParamException('Existing records must contain an Id');
        }

        $route = $this->schema->route($this->getPrimaryKey());
        if ($route == null) {
            throw new NotSupportedException(
                'API does not support deleting ' . $this->schema->name . ' resources'
            );
        }

        $response = $this->getWrapper()->client->delete(
            $route
        );
        if ($response === false) {
            return false;
        }

        return true;
    }

    public function setParentResource($resourceName, $primaryKey, $foreignName)
    {
        $this->_parentResourceName = $resourceName;
        $this->_parentResourceKey = $primaryKey;
        $this->_parentForeignName = $foreignName;
    }

    public static function instantiate($data, $schema)
    {
        return new static(
            [
                'resourceName' => $schema->name,
            ]
        );
    }

    public static function populateRecord($record, $data)
    {
        /** @var ResourceInfo $schema */
        $schema = $record->schema;

        $record->clearData();

        foreach ($data as $key => $value) {
            $field = $schema->fieldName($key);
            if ($field) {
                $dataType = $schema->fieldDataType($field);
                if ($dataType === null) {
                    throw UnknownDataTypeException::create($record->_resourceName, $dataType);
                }
                $record->{$field} = $dataType->fromApi($value);
                continue;
            }

            $relation = $schema->relationName($key);
            if ($relation) {
                $record->populateRelation($relation, $value);
            }
        }

        $record->setOldAttributes([]);
    }
}
