<?php
/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:52 PM
 */

require_once("../src/base.php");

$worker = new Worker();
$user   = Session::getInstance()->getUser();

if (isset($_GET['login'])) {
    $worker->login();
}

if (isset($_GET['signup'])) {
    $worker->signup();
}

if (isset($_GET['logout'])) {
    $worker->logout();
}

if (isset($_GET['upload'])) {
    $worker->upload($user);
}

if (isset($_GET['token'])) {
    $worker->token($_GET['token']);
}

if (isset($_GET['search_string'])) {
    $_SESSION['search_string'] = $_GET['search_string'];
    $_SESSION['search_user']   = $_GET['user_id'];
}

if (isset($_GET['delete'])) {
    $worker->deletePicture((int)$_GET['id'], $user->id);
}

if (isset($_GET['activation']) && $user->is_admin) {
    $worker->activateUser((int)$_GET['id']);
    header('Location: ' . APP_BASE . 'users.php');
    exit;
}

if (isset($_GET['deletion']) && $user->is_admin) {
    $worker->deleteUser((int)$_GET['id']);
    header('Location: ' . APP_BASE . 'users.php');
    exit;
}

if (isset($_GET['deleteAdm'])) {
    $worker->deletePicture((int)$_GET['id']);
}

header('Location: ' . APP_BASE . 'index.php');
exit;