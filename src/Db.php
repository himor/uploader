<?php

/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:30 PM
 */
class Db
{
    private $configuration = [];

    private        $handler  = null;
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function setup(array $data)
    {
        $this->configuration = $data;
    }

    private function connect()
    {
        $this->handler = new mysqli(
            $this->configuration['db_host'],
            $this->configuration['db_user'],
            $this->configuration['db_pasw'],
            $this->configuration['db_name']
        );

        if ($this->handler->connect_errno > 0) {
            throw new Exception('Unable to connect to database [' . $this->handler->connect_error . ']');
        }
    }

    public function query($sql)
    {
        if (!$this->handler) {
            $this->connect();
        }
        if (!$result = $this->handler->query($sql)) {
            throw new Exception('There was an error running the query [' . $this->handler->error . ']');
        }
        return $result;
    }

    public function getLastId()
    {
        if (!$this->handler) {
            throw new Exception('Improper function call ' . __FUNCTION__);
        }

        return $this->handler->insert_id;
    }

    /* kind of ORM */

    private function orm_($resource, $forceMulti = false)
    {
        $array = $this->convert($resource);
        if (count($array) == 1 && !$forceMulti) {
            return $this->atoo($array[0]);
        } else {
            $obj = array();
            foreach ($array as $a)
                $obj[] = $this->atoo($a);
            return $obj;
        }
    }

    private function atoo($array)
    {
        $obj = new \stdClass();
        foreach ($array as $key => $value)
            if (!is_numeric($key))
                $obj->$key = $value;
        return $obj;
    }

    private function convert($resource)
    {
        $array = array();
        while ($r = $resource->fetch_assoc()) {
            $array[] = $r;
        }
        return $array;
    }

    private function _getOne($resource)
    {
        $many = $this->orm_($resource, true);
        if (!empty($many)) return $many[0];
        return array();
    }

    private function _getMany($resource)
    {
        return $this->orm_($resource, true);
    }

    public function getOne($sql)
    {
        return $this->_getOne($this->query($sql));
    }

    public function getMany($sql)
    {
        return $this->_getMany($this->query($sql));
    }

} 