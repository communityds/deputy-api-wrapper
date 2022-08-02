<?php

namespace CommunityDS\Deputy\Api\Schema;

use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\Model\ModelInterface;
use CommunityDS\Deputy\Api\Query;
use CommunityDS\Deputy\Api\WrapperLocatorTrait;

/**
 * Defines the schema for a specific API Resource.
 *
 * Represents the information retrieved via the /resource/:object/INFO call.
 *
 * @see https://www.deputy.com/api-doc/API/Resource_Calls
 */
class ResourceInfo extends Component
{
    use WrapperLocatorTrait;

    /**
     * Name of the resource.
     *
     * @var string
     */
    public $name;

    /**
     * Plural name of resource.
     *
     * @var string
     */
    private $_pluralName;

    /**
     * Name of class used to initialise a resource.
     *
     * @var string
     */
    public $modelClass;

    /**
     * Indicates if the resource definitions have been fetched
     * from the API. If all definitions have been manually defined,
     * then set this to `true` to stop a call to the API.
     *
     * @var boolean
     */
    public $warm = false;

    /**
     * Indicates the API endpoints that are used to retrieve information.
     * If true, then Resource endpoints are used; if null then there are
     * no endpoints; if an array then those endpoints are used.
     *
     * @var boolean|array
     */
    public $endpoints = true;

    /**
     * Configuration of fields where key is field name
     * and value is data type.
     *
     * @var string[]
     */
    public $fields = [];

    /**
     * Configuration of joins where key is join name
     * and value is Resource Name.
     *
     * @var string[]
     */
    public $joins = [];

    /**
     * Configuration of associations where key is association name
     * and value is Resource Name.
     *
     * @var string[]
     */
    public $assocs = [];

    /**
     * Configuration of aliases where key is alias name and value is
     * Field, Join or Association name.
     *
     * @var string[]
     */
    public $aliases = [];

    /**
     * Cache of lookup tables.
     *
     * @var array
     */
    private $_cache = [];

    public function init()
    {
        parent::init();
    }

    /**
     * Returns the singluar name of this resource.
     *
     * @return string
     */
    public function getSingularName()
    {
        return $this->name;
    }

    /**
     * Returns the plural name for this resource.
     *
     * @return string
     */
    public function getPluralName()
    {
        if ($this->_pluralName) {
            return $this->_pluralName;
        }
        return $this->name . 's';
    }

    /**
     * Sets the plural name for this resource.
     *
     * @param string $pluralName Plural name
     *
     * @return void
     */
    public function setPluralName($pluralName)
    {
        $this->_pluralName = $pluralName;
    }

    /**
     * Loads the model definitions from the API if not already defined.
     *
     * @return void
     */
    public function loadDefinitions()
    {

        $details = $this->getWrapper()->client->get(
            $this->route('INFO')
        );

        $this->assocs = array_merge(
            $details['assocs'],
            $this->assocs ? $this->assocs : []
        );
        $this->fields = array_merge(
            $details['fields'],
            $this->fields ? $this->fields : []
        );
        $this->joins = array_merge(
            $details['joins'],
            $this->joins ? $this->joins : []
        );

        $this->warm = true;
    }

    /**
     * Returns the field name as defined within the API.
     *
     * @param string $name Field name
     *
     * @return string|null Field name defined within API; or null if not a field
     */
    public function fieldName($name)
    {
        $key = 'f:' . strtolower($name);
        if (!array_key_exists($key, $this->_cache)) {
            $this->_cache[$key] = null;
            foreach ($this->fields as $fieldName => $dataType) {
                $this->_cache['f:' . strtolower($fieldName)] = $fieldName;
            }
        }
        return $this->_cache[$key];
    }

    /**
     * Returns the data type for a specified field.
     *
     * @param string $name Field name
     *
     * @return DataTypeInterface|null
     */
    public function fieldDataType($name)
    {
        $fieldName = $this->fieldName($name);
        if ($fieldName && array_key_exists($fieldName, $this->fields)) {
            return $this->getWrapper()->schema->dataType($this->fields[$fieldName]);
        }
        return null;
    }

    /**
     * Returns the attribute name based on the field name defined within the API.
     *
     * @param string $name Field name
     *
     * @return string|null
     */
    public function attributeName($name)
    {
        return strtolower(substr($name, 0, 1)) . substr($name, 1);
    }

    /**
     * Returns the join name as defined within the API.
     *
     * @param string $name Join name
     *
     * @return string|null Join name defined within API; or null if not a relation
     */
    public function joinName($name)
    {
        $key = 'j:' . strtolower($name);
        if (!array_key_exists($key, $this->_cache)) {
            $this->_cache[$key] = null;
            foreach ($this->joins as $joinName => $resourceName) {
                $this->_cache['j:' . strtolower($joinName)] = $joinName;
            }
        }
        return $this->_cache[$key];
    }

    /**
     * Returns the resource type for a specified join.
     *
     * @param string $name Join name
     *
     * @return string|null Resource name
     */
    public function joinResource($name)
    {
        $joinName = $this->joinName($name);
        if ($joinName && array_key_exists($joinName, $this->joins)) {
            return $this->joins[$joinName];
        }
        return null;
    }

    /**
     * Returns the association name as defined within the API.
     *
     * @param string $name Association name
     *
     * @return string|null Association name defined within API; or null if not a relation
     */
    public function assocName($name)
    {
        $key = 'a:' . strtolower($name);
        if (!array_key_exists($key, $this->_cache)) {
            $this->_cache[$key] = null;
            foreach ($this->assocs as $assocName => $resourceName) {
                $this->_cache['a:' . strtolower($assocName)] = $assocName;
            }
        }
        return $this->_cache[$key];
    }

    /**
     * Returns the resource type for a specific association.
     *
     * @param string $name Association name
     *
     * @return string|null Resource name
     */
    public function assocResource($name)
    {
        $assocName = $this->assocName($name);
        if ($assocName && array_key_exists($assocName, $this->assocs)) {
            return $this->assocs[$assocName];
        }
        return null;
    }

    /**
     * Returns the relationship name as defined within the API.
     *
     * @param string $name Name to find
     *
     * @return string|null Join or Association name defined within API; or null if not a relation
     */
    public function relationName($name)
    {
        $relation = $this->joinName($name);
        if ($relation == null) {
            $relation = $this->assocName($relation);
        }
        return $relation;
    }

    /**
     * Returns the resource type for a specific relationship.
     *
     * @param string $name Name to find
     *
     * @return string|null Resource name
     */
    public function relationResource($name)
    {
        $relation = $this->joinName($name);
        if ($relation) {
            return $this->joinResource($relation);
        }
        $relation = $this->assocName($name);
        if ($relation) {
            return $this->assocResource($relation);
        }
        return null;
    }

    /**
     * Creates new instance of the resource.
     *
     * @param array $data Initial data
     *
     * @return ModelInterface
     */
    public function create($data = [])
    {
        $class = $this->modelClass;
        if ($data instanceof $class) {
            return $data;
        }
        $instance = $class::instantiate($data, $this);
        $instance::populateRecord($instance, $data);
        return $instance;
    }

    /**
     * Returns a single object that matches the primary key.
     *
     * @param string $id Primary key
     *
     * @return ModelInterface
     */
    public function findOne($id)
    {
        return $this->find()->where(['id' => $id])->one();
    }

    /**
     * Returns an object used to query this resource.
     *
     * @return Query
     */
    public function find()
    {
        return new Query(
            [
                'resourceName' => $this->name,
            ]
        );
    }

    /**
     * Converts this schema to a configuration array that can be stored.
     *
     * @return array
     */
    public function toConfig()
    {
        return [
            'name' => $this->name,
            'fields' => $this->fields,
            'joins' => $this->joins,
            'assocs' => $this->assocs,
        ];
    }

    /**
     * Returns the route to the API endpoint.
     *
     * @param integer|null $id Identifier
     * @param string|null $relation Relationship name
     *
     * @return string
     */
    public function route($id = null, $relation = null)
    {
        if ($this->endpoints === true) {
            return 'resource/' . $this->name . ($id ? ('/' . $id . ($relation ? ('/' . $relation) : '')) : '');
        }
        if ($this->endpoints) {
            if ($id && array_key_exists('id', $this->endpoints)) {
                return str_replace(':Id', $id, $this->endpoints['id']);
            }
        }
        return null;
    }

    /**
     * Returns the PHPDoc tags for the model based on the field,
     * join and assocation definitions.
     *
     * @return string[]
     */
    public function getModelPhpDoc()
    {
        $lines = [];
        $schema = $this->getWrapper()->schema;

        foreach ($this->fields as $name => $type) {
            $dataType = $schema->dataType($type);
            $lines[] = '@property ' . $dataType->phpType() . ' $' . $this->attributeName($name);
        }

        if (count($this->joins) > 0) {
            $lines[] = '';
            foreach ($this->joins as $name => $class) {
                $lines[] = '@property ' . $class . ' $' . $this->attributeName($name);
            }
        }

        if (count($this->assocs) > 0) {
            $lines[] = '';
            foreach ($this->assocs as $name => $class) {
                $lines[] = '@property ' . $class . ' $' . $this->attributeName($name);
            }
        }

        return $lines;
    }

    /**
     * Returns the PHPDoc tags for the wrapper class.
     *
     * @return string[]
     */
    public function getWrapperPhpDoc()
    {
        $lines = [];
        $modelClass = str_replace('CommunityDS\Deputy\Api\\', '', $this->modelClass);
        if ($this->endpoints === true) {
            $lines[] = '@method ' . $modelClass . ' create' . $this->getSingularName() . '()';
            $lines[] = '@method ' . $modelClass . ' delete' . $this->getSingularName() . '($id)';
            $lines[] = '@method ' . $modelClass . ' get' . $this->getSingularName() . '($id)';
            if ($this->getSingularName() != $this->getPluralName()) {
                $lines[] = '@method ' . $modelClass . '[] get' . $this->getPluralName() . '()';
            }
            $lines[] = '@method Query find' . $this->getPluralName() . '()';
        } elseif (is_array($this->endpoints)) {
            foreach ($this->endpoints as $method => $uri) {
                if ($method == 'id') {
                    $lines[] = '@method ' . $modelClass . ' get' . $this->getSingularName() . '($id)';
                }
            }
        }
        return $lines;
    }
}
