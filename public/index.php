<?php
/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:19 PM
 */

require_once("../src/base.php");

include_once(APP_PUBLIC . "header.php");

if (!$user) :
    ?>

    <div class="d1">
        <div class="d2">
            <h3>Login</h3>

            <form method="post" action="<?php echo APP_BASE ?>worker.php?login">
                <label>Email</label>
                <input type="email" maxlength="255" placeholder="Your email address" name="email" required="required">

                <label>Password</label>
                <input type="password" placeholder="password" name="password" required="required">

                <input type="submit" value="Log in">
            </form>
        </div>
        <div class="d2">
            <h3>Registration</h3>

            <form method="post" action="<?php echo APP_BASE ?>worker.php?signup" id="signupform">
                <label>Email</label>
                <input type="email" maxlength="255" placeholder="Your email address" name="email" required="required">

                <label>Password</label>
                <input id="pasw1" type="password" placeholder="password" name="password" required="required">

                <label>Re-type Password</label>
                <input id="pasw2" type="password" placeholder="password" required="required">

                <input type="submit" value="Sign up">
            </form>
        </div>
    </div>
<?php elseif (!$user->is_admin) : ?>
    <div class="d1">
        <h3>My images</h3>

        <form method="post" action="<?php echo APP_BASE ?>worker.php?upload" enctype="multipart/form-data">
            <h4>Upload new</h4>
            <label>Name</label>
            <input type="text" maxlength="255" placeholder="Name of the picture" name="title" required="required">
            <input type="file" name="picture" required="required">
            <input type="submit" value="Upload">
        </form>
    </div>

    <div class="d1 pictures">
        <?php
        $pictures = Worker::loadUserPics($user->id);

        if ($pictures) :
            foreach ($pictures as $picture) {
                ?>
                <div class="picture">
                    <p>
                        <a href="<?php echo $picture->getUrl() ?>"><?php echo $picture->title ?></a>
                        <small><?php echo date('Y-m-d H:i', $picture->created_at); ?></small>
                    </p>
                    <img class="mini" src="<?php echo $picture->getUrl() ?>"/>
                    <a href="<?php echo APP_BASE ?>worker.php?delete&id=<?php echo $picture->id ?>">delete</a>
                </div>
            <?php
            }
        else: ?>

            <p>You don't have any pictures. Upload one!</p>

        <?php endif; ?>

        <div class="clear"></div>
    </div>

<?php
elseif ($user->is_admin):
    include_once(APP_PUBLIC . "admin.php");

endif;
include_once(APP_PUBLIC . "footer.php");
