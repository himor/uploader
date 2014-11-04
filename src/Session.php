<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/3/14
 * Time: 3:55 PM
 */
class Session
{

    private $user = null;

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

    private function load()
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        if ($userId) {
            $db         = Db::getInstance();
            $userObject = $db->getOne(
                'SELECT * FROM `user` WHERE id = ' . (int)$userId
            );
            $this->user = new User($userObject);
        }
    }

    public function getUser()
    {
        $this->load();
        return $this->user;
    }

    public function setUser($id) {
        $_SESSION['user_id'] = $id;
    }

    public function forget()
    {
        unset($_SESSION['user_id']);
        $this->user = null;
    }

    public function pushMessage($message)
    {
        $_SESSION['message'] = $message;
    }

    public function pullMessage()
    {
        $r = $_SESSION['message'];
        unset($_SESSION['message']);
        return $r;
    }

} 