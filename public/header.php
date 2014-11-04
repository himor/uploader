<?php
/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:51 PM
 */

$user = Session::getInstance()->getUser();

?>

    <!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Picture uploader</title>
        <link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic' rel='stylesheet'
              type='text/css'>
        <link rel="stylesheet" href="/css/main.css">
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    </head>
<body>

    <div class="menu">
        <?php
        if ($user) :
            ?>
            <span class="menu-user">User: <strong><?php echo $user->email ?></strong>
                    <?php if ($user->is_admin): ?>
                        &middot; ADMIN
                    <?php endif; ?>
                </span>

            <?php if ($user->is_admin): ?>
                <span class="menu">
                    <a href="<?php echo APP_BASE ?>index.php">Pictures</a>
                </span>
                <span class="menu">
                    <a href="<?php echo APP_BASE ?>users.php">Users</a>
                </span>
            <?php endif; ?>

            <span class="menu">
                <a href="<?php echo APP_BASE ?>worker.php?logout">Log out</a>
            </span>

        <?php else: ?>

        <?php endif; ?>
    </div>

<div class="content">

    <h1>Uploader</h1>

<?php
$message = Session::getInstance()->pullMessage();
if ($message) :
    ?>
    <div class="message">
        <p><?php echo $message; ?></p>
    </div>
<?php
endif;
?>