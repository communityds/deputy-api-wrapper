<?php

namespace CommunityDS\Deputy\Api;

use CommunityDS\Deputy\Api\Adapter\ClientInterface;
use CommunityDS\Deputy\Api\Model\ModelInterface;
use CommunityDS\Deputy\Api\Schema\ResourceInfo;
use CommunityDS\Deputy\Api\Schema\UnknownFieldException;
use CommunityDS\Deputy\Api\Schema\UnknownRelationException;

/**
 * Represents a query on a specific type of model.
 */
class Query extends Component
{
    use WrapperLocatorTrait;

    /**
     * Maximum number of records API can return per request.
     */
    const BATCH_MAX = 500;

    /**
     * Name of the resource being queried.
     *
     * @var string
     */
    public $resourceName;

    /**
     * Instance of the parent object to return joins or associations for.
     *
     * @var ModelInterface
     */
    public $primaryModel;

    /**
     * Name of the foreign object to return.
     *
     * @var string
     */
    public $foreignName;

    /**
     * Where conditions.
     *
     * @var array
     *
     * @see where()
     * @see andWhere()
     */
    public $where = [];

    /**
     * Pre-loaded results from the API.
     *
     * @var array
     */
    public $data = null;

    /**
     * Maximum number of records to return.
     *
     * @var integer
     *
     * @see limit()
     */
    public $limit = null;

    /**
     * Maximum number of records to return per API call / batch.
     *
     * @var integer
     *
     * @see batchSize()
     */
    public $batchSize = self::BATCH_MAX;

    /**
     * Starting position.
     *
     * @var integer
     *
     * @see offset()
     */
    public $offset = null;

    /**
     * Join conditions.
     *
     * @var string[]
     *
     * @see joinWith()
     */
    public $join = [];

    /**
     * Order by clause.
     *
     * @var string[]
     *
     * @see orderBy()
     */
    public $orderBy = null;

    /**
     * Returns resource schema.
     *
     * @return ResourceInfo
     */
    protected function getSchema()
    {
        return $this->getWrapper()->schema->resource($this->resourceName);
    }

    /**
     * Returns current API client.
     *
     * @return ClientInterface
     */
    protected function getClient()
    {
        return $this->getWrapper()->client;
    }

    /**
     * Set the maximum number of records to return.
     *
     * @param integer $limit Number of records
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set the starting offset (0 as first).
     *
     * @param integer $offset Starting offset (0 as first)
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Sets the maximum number of records to retrieve from the
     * Deputy API.
     *
     * @param integer $batchSize Number of records
     *
     * @return $this
     */
    public function batchSize($batchSize)
    {
        $this->batchSize = $batchSize;
        return $this;
    }

    /**
     * Sets the order by clause used to sort the results.
     *
     * @param array $orderBy Array where key is attribute name and value
     *                       is either `SORT_ASC` or `SORT_DESC` constant.
     *
     * @return $this
     */
    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Returns the first record within the result set.
     *
     * @return ModelInterface|null
     */
    public function one()
    {
        foreach ($this->all() as $result) {
            return $result;
        }
        return null;
    }

    /**
     * Sets the where condition.
     *
     * @param array $conditions Condition structure which is either:
     *                          - A hash where keys are field name and values are a scalar value
     *                            or an array of scalar values.
     *                            `['id' => 1]` or `['id' => [1, 2, 3], 'name' => 'John']`
     *                          - An array with field name, operator and value (scalar or array).
     *                            `['=', 'id', 1]` or `[ ['in', 'id', [1, 2, 3]], ['=', 'name', 'John'] ]`
     *                            Valid operators are `=`, `!=`, `>`, `>=`, `<`, `<=`, `like`, `not like`,
     *                            `in`, `not in`, `empty` and `not empty`
     *
     * @return $this
     */
    public function where($conditions)
    {
        $this->where = [];
        $this->andWhere($conditions);
        return $this;
    }

    /**
     * Adds another condition to the query.
     *
     * @param array $conditions Conditions
     *
     * @return $this
     *
     * @see where() for examples of conditions
     */
    public function andWhere($conditions)
    {
        foreach ($conditions as $key => $val) {
            if (is_string($key)) {
                $this->where[] = ['in', $key, $val];
            } elseif ($key == 0 && is_string($val)) {
                $this->where[] = $conditions;
                break;
            } else {
                $this->where[] = $val;
            }
        }
        return $this;
    }

    /**
     * Adds a `join` or `assoc` rule that pre-loads the related records
     * via the API.
     *
     * @param string $name Name of join or association
     *
     * @return $this
     */
    public function joinWith($name)
    {
        $this->join[] = $name;
        return $this;
    }

    /**
     * Returns all the records.
     *
     * @return ModelInterface[]
     */
    public function all()
    {
        return $this->queryAll();
    }

    /**
     * Sends the query to the API and returns the converted response.
     *
     * @return ModelInterface[]
     */
    protected function queryAll()
    {
        $models = [];
        $schema = $this->getSchema();
        foreach ($this->sendQuery() as $row) {
            $model = $schema->create($row);
            if ($this->primaryModel && $this->foreignName) {
                $id = $this->primaryModel->getPrimaryKey();
                if ($id) {
                    $model->setParentResource(
                        $this->primaryModel->getSchema()->name,
                        $this->primaryModel->getPrimaryKey(),
                        $this->foreignName
                    );
                }
            }
            $models[] = $model;
        }
        return $models;
    }

    /**
     * Sends the query to the API and returns the response.
     *
     * @return array
     *
     * @throws NotSupportedException When API route can not be determined
     * @throws InvalidParamException When there is an issue with the query
     * @throws UnknownFieldException When adding a condition on an unknown attribute
     * @throws UnknownRelationException When joining on an unknown relation
     */
    protected function sendQuery()
    {

        $schema = $this->getSchema();

        // Pre-loaded data
        if ($this->data !== null) {
            if (!isset($this->data[0])) {
                return [$this->data];
            }
            return $this->data;
        }

        // User information
        if ($schema->name == 'Me') {
            $response = $this->getClient()->get('me');
            if ($response) {
                return [$response];
            }
            return [];
        }

        // Foreign object
        if ($this->foreignName) {
            $primarySchema = $this->primaryModel->getSchema();
            $id = $this->primaryModel->getPrimaryKey();
            if ($id) {
                $route = $primarySchema->route($id, $this->foreignName);
                if ($route == null) {
                    throw new NotSupportedException(
                        'No route specified to find ' . $primarySchema->name . '/' . $this->foreignName
                    );
                }
                $response = $this->getClient()->get($route);
                if ($response) {
                    return [$response];
                }
            }
            return [];
        }

        // Standardises the where condition
        $search = [];
        foreach ($this->where as $key => $val) {
            if (!is_array($val) || count($val) != 3) {
                throw new InvalidParamException(
                    'Unsupported condition format: ' . var_export($val, true)
                );
            }

            $operator = $this->sanitiseOperator($val[0]);

            $field = $val[1];
            $fieldName = $schema->fieldName($field);
            if ($fieldName == null) {
                throw UnknownFieldException::create($schema, $field);
            }

            $dataType = $schema->fieldDataType($fieldName);
            $data = $val[2];
            if (is_array($data)) {
                if ($operator == 'eq') {
                    $operator = $this->sanitiseOperator('in');
                } elseif ($operator == 'ne') {
                    $operator = $this->sanitiseOperator('not in');
                } elseif ($operator == 'in' || $operator == 'nn') {
                    // allowed
                } else {
                    throw new InvalidParamException(
                        'Unable to support multiple values for ' . var_export($val, true) . ' condition'
                    );
                }
                foreach ($data as $k => $v) {
                    $data[$k] = $dataType->toApi($v);
                }
            } else {
                $data = $dataType->toApi($data);
                if ($operator == 'in') {
                    $operator = $this->sanitiseOperator('=');
                } elseif ($operator == 'nn') {
                    $operator = $this->sanitiseOperator('!=');
                }
            }

            $condition = [
                'field' => $fieldName,
                'type' => $operator,
                'data' => $data,
            ];
            $search[] = $condition;
        }

        // Check if getting by primary key
        $id = null;
        if (count($search) == 1) {
            foreach ($search as $condition) {
                if (
                    $condition['field'] == 'Id'
                    && ($condition['type'] == 'eq' || $condition['type'] == 'in')
                    && is_scalar($condition['data'])
                ) {
                    $id = $condition['data'];
                    break;
                }
            }
        }

        // Retrieve via primary key
        if ($id && count($this->join) == 0) {
            $response = $this->getClient()->get(
                $schema->route($id)
            );
            if ($response) {
                return [$response];
            }
            return [];
        }

        // Build QUERY request
        $request = [];

        if (count($search) > 0) {
            $request['search'] = $search;
        }

        if (count($this->join) > 0) {
            $request['join'] = [];
            $request['assoc'] = [];
            foreach ($this->join as $name) {
                $relation = $schema->joinName($name);
                if ($relation) {
                    $request['join'][] = $relation;
                } else {
                    $relation = $schema->assocName($name);
                    if ($relation) {
                        $request['assoc'][] = $relation;
                    } else {
                        throw UnknownRelationException::create($schema, $name);
                    }
                }
            }
            if (count($request['join']) == 0) {
                unset($request['join']);
            }
            if (count($request['assoc']) == 0) {
                unset($request['assoc']);
            }
        }

        if ($this->orderBy) {
            $request['sort'] = [];
            if (is_array($this->orderBy)) {
                foreach ($this->orderBy as $name => $dir) {
                    $fieldName = $schema->fieldName($name);
                    if ($fieldName) {
                        if ($dir === SORT_ASC) {
                            $request['sort'][$fieldName] = 'asc';
                        } elseif ($dir === SORT_DESC) {
                            $request['sort'][$fieldName] = 'desc';
                        } else {
                            throw new InvalidParamException(
                                'Invalid order by direction: ' . var_export($dir, true)
                            );
                        }
                    } else {
                        throw new InvalidParamException(
                            'Unknown field in order by clause: ' . var_export($name, true)
                        );
                    }
                }
            } else {
                throw new InvalidParamException(
                    'Order by clause must be array of field name and direction'
                );
            }
        }

        $request['max'] = $this->batchSize;
        $offset = $this->offset ? $this->offset : 0;
        $remaining = $this->limit ? $this->limit : $this->batchSize;

        // Load data in batches
        $items = [];
        do {
            $request['start'] = $offset;
            $request['max'] = min(
                $this->batchSize,
                $remaining
            );

            $result = $this->getClient()->post(
                $schema->route('QUERY'),
                $request
            );
            $items = array_merge(
                $items,
                $result
            );

            $count = count($result);
            if ($count < $request['max']) {
                $remaining = 0;
            } else {
                $offset += $count;
                if ($this->limit) {
                    $remaining -= $count;
                }
            }
        } while ($remaining > 0);

        return $items;
    }

    /**
     * Returns the applicable search operator from the original.
     *
     * @param string $operator Original operator
     *
     * @return string
     *
     * @throws InvalidParamException If operator is not known
     */
    protected function sanitiseOperator($operator)
    {
        switch (strtolower($operator)) {
            case '=':
            case 'eq':
                return 'eq';
            case '>':
            case 'gt':
                return 'gt';
            case '>=':
            case 'ge':
                return 'ge';
            case '<':
            case 'lt':
                return 'lt';
            case '<=':
            case 'le':
                return 'le';
            case 'like':
            case 'lk':
                return 'lk';
            case 'not like':
            case 'nk':
                return 'nk';
            case '!=':
            case 'ne':
                return 'ne';
            case 'in':
                return 'in';
            case 'not in':
            case 'nn':
                return 'nn';
            case 'not empty':
            case 'is':
                return 'is';
            case 'empty':
            case 'ns':
                return 'ns';
        }
        throw new InvalidParamException('Unknown operator ' . $operator);
    }
}
