<?php

/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:26 PM
 */
class User
{
    public $id;
    public $email;
    public $password;
    public $active     = false;
    public $is_admin   = false;
    public $is_deleted = false;
    public $created_at;
    public $token;

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

    public static function getToken()
    {
        return md5(rand(100000, 999999)) . md5(microtime(true));
    }

}