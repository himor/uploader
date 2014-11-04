<?php
/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:57 PM
 */

require_once("../src/base.php");

$user = Session::getInstance()->getUser();
if (!$user->is_admin) {
    header('Location: ' . APP_BASE . 'index.php');
    exit;
}

include_once(APP_PUBLIC . "header.php");

$users = Worker::loadUsers(false);
?>

<h3>Users</h3>

<table>
    <thead>
    <tr>
        <th>id</th>
        <th>email</th>
        <th>is active</th>
        <th>is_deleted</th>
        <th>created</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $item): ?>
    <tr>
        <td><?php echo $item->id ?></td>
        <td><?php echo strtolower($item->email) ?></td>
        <td><?php echo $item->active ? 'yes <a href="'.APP_BASE.'worker.php?activation&id='.$item->id.'">deactivate</a>' : 'no <a href="'.APP_BASE.'worker.php?activation&id='.$item->id.'">activate</a>' ?></td>
        <td><?php echo $item->is_deleted ? 'yes <a href="'.APP_BASE.'worker.php?deletion&id='.$item->id.'">undelete</a>' : 'no <a href="'.APP_BASE.'worker.php?deletion&id='.$item->id.'">delete</a>' ?></td>
        <td><?php echo date('Y-m-d H:i', $item->created_at);?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

