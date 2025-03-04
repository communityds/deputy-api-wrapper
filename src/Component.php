<?php

namespace CommunityDS\Deputy\Api;

/**
 * Basic component that allows properties to be automatically populated by the
 * constructor; ensures that all properties must be declared; or alternatively
 * implemented via getter and setter methods.
 */
class Component
{
    /**
     * Creates a new instance of this component and populates
     * the properties from the configuration array.
     *
     * @param array $config Initial property values
     */
    public function __construct($config = [])
    {
        static::configure($this, $config);
        $this->init();
    }

    /**
     * Over-loadable function that is called at the end of the constructor
     * after the properties have been automatically populated.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Returns the value of a property via a getter function, or throws an
     * exception if the function is not found or the property is write-only.
     *
     * @param string $name Name of property
     *
     * @return mixed
     *
     * @throws InvalidParamException If the property is unknown or write-only
     */
    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (method_exists($this, 'set' . ucfirst($name))) {
            throw new InvalidParamException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new InvalidParamException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Sets the value of a property via a setter function, or throws an
     * exception if the function is not found or the property is read-only.
     *
     * @param string $name Name of property
     * @param mixed $value Value of property
     *
     * @throws InvalidParamException If the property is unknown or read-only
     */
    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
            return;
        }

        if (method_exists($this, 'get' . ucfirst($name))) {
            throw new InvalidParamException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }

        throw new InvalidParamException('Setting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Configures an object using the publicly available interfaces.
     *
     * @param Component $component The component to update
     * @param array $config The properties to update
     *
     * @return Component The updated object
     */
    public static function configure($component, $config)
    {
        foreach ($config as $name => $value) {
            $component->{$name} = $value;
        }
        return $component;
    }

    /**
     * Creates an instance of an object.
     *
     * @param string|array $class The class name; or configuration array
     * @param array $config The configuration array
     *
     * @return Component
     *
     * @throws InvalidParamException When class definition is missing from the configuration array
     */
    public static function createObject($class, $config = [])
    {
        if ($class !== null && !is_string($class)) {
            $config = $class;
            $class = null;
            if (array_key_exists('class', $config)) {
                $class = $config['class'];
                unset($config['class']);
            }
        }
        if ($class === null) {
            throw new InvalidParamException('Missing class definition in configuration array');
        }
        return new $class($config);
    }
}
