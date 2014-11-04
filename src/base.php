<?php
/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:24 PM
 */

define ('APP_PUBLIC', __DIR__ . '/../public/');
define ('APP_SRC', __DIR__ . '/../src/');
define ('APP_UPLOAD', APP_PUBLIC . 'upload/');
define ('APP_BASE', 'http://uploader/');

function __autoload($classname)
{
    $filename = APP_SRC . $classname . ".php";
    include_once($filename);
}

session_start();
$session_start = time();

Db::getInstance()->setup([
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pasw' => 'root',
    'db_name' => 'uploader'
]);
