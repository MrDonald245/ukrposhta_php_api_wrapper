<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 04/04/18
 * Time: 14:23
 */

abstract class EntityBase
{
    abstract function toArray();

    /**
     * EntityBase constructor.
     *
     * @param array|null $array_data
     */
    abstract function __construct($array_data = null);

    /**
     * @param array $data
     * @return void
     */
    abstract function initWithArray($data);

    /**
     * @return array
     */
    protected function objectToArray() {
        $reflectionClass = new ReflectionClass(get_class($this));
        $array = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }

        return $array;
    }

    protected function arrayToObject($array) {
        foreach ($array as $key => $value) {
            $this->{$key} = $value;
        }
    }
}