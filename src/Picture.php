<?php

/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:29 PM
 */
class Picture
{
    public $id;
    public $user_id;
    public $name;
    public $title;
    public $created_at;

    public function __construct($data = null)
    {
        $this->created_at = time();
        if (is_object($data)) {
            foreach (get_object_vars($data) as $key => $value) {
                $this->$key = $value;
            }
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getPath()
    {
        return APP_UPLOAD . $this->name;
    }

    public function getUrl()
    {
        return APP_BASE . 'upload/' . $this->name;
    }

    public static function generateName()
    {
        return md5(microtime(true)) . '.jpg';
    }
}