<?php
/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 3:55 PM
 */

if (!isset($user)) {
    throw new Exception('Unauthorized');
}

$users = Worker::loadUsers();

$search_string = $_SESSION['search_string'];
$search_user   = $_SESSION['search_user'];
?>

<h3>Search</h3>

<form method="get" action="<?php echo APP_BASE ?>worker.php">
    <label>Title</label>
    <input type="text" name="search_string" placeholder="Search string" value="<?php echo $search_string ? $search_string : ''?>">

    <br>

    <label>User</label>
    <select name="user_id">
        <option value="">Select user</option>
        <?php
        foreach ($users as $user):
            if ($user->is_admin) continue;
            ?>
            <option <?php echo $search_user && $search_user==$user->id ? 'selected="selected"' : ''?> value="<?php echo $user->id ?>"><?php echo $user->email ?></option>
        <?php
        endforeach;
        ?>
    </select>

    <input type="submit">
</form>

<h3>Images</h3>

<div class="d1 pictures">
    <?php
    $pictures = Worker::loadSearchPics($search_string, $search_user);

    if ($pictures) :
        foreach ($pictures as $picture) {
            ?>
            <div class="picture">
                <p>
                    <a href="<?php echo $picture->getUrl() ?>"><?php echo $picture->title ?></a>
                    <small><?php echo date('Y-m-d H:i', $picture->created_at); ?></small>
                    <small><?php echo $picture->email ?></small>
                </p>
                <img class="mini" src="<?php echo $picture->getUrl() ?>"/>
                <a href="<?php echo APP_BASE ?>worker.php?deleteAdm&id=<?php echo $picture->id ?>">delete</a>
            </div>
        <?php
        }
    else: ?>

        <p>Not found.</p>

    <?php endif; ?>

    <div class="clear"></div>
</div>

