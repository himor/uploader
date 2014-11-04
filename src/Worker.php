<?php

/**
 * Created by PhpStorm.
 * author Mike Gordo <mgordo@live.com>
 * Date: 11/3/14
 * Time: 4:29 PM
 */
class Worker
{
    /**
     * Handles login
     */
    public function login()
    {
        $email = strtoupper($_POST['email']);
        $passw = hash('sha256', $_POST['password']);

        if (!$email || !$passw) {
            Session::getInstance()->pushMessage('Incorrect input');
            return;
        }

        $user = Db::getInstance()->getOne(
            "SELECT * FROM `user` WHERE `email` = '" . $email . "' AND `password` = '" . $passw . "' LIMIT 1"
        );

        if (!$user) {
            Session::getInstance()->pushMessage('Incorrect email address or password');
            return;
        }

        $user = new User($user);

        if (!$user->active) {
            Session::getInstance()->pushMessage('Please confirm the email address');
            return;
        }

        Session::getInstance()->setUser($user->id);
    }

    /**
     * Handles registration
     * @throws Exception
     */
    public function signup()
    {
        $email = strtoupper($_POST['email']);
        $passw = hash('sha256', $_POST['password']);

        if (!$email || !$passw) {
            Session::getInstance()->pushMessage('Incorrect input');
            return;
        }

        $user = Db::getInstance()->getOne(
            "SELECT * FROM `user` WHERE `email` = '" . $email . "' AND `is_deleted` = FALSE LIMIT 1"
        );

        if ($user) {
            Session::getInstance()->pushMessage('User with this email already exists');
            return;
        }

        $token = User::getToken();

        Db::getInstance()->query(
            "INSERT INTO `user` (`email`, `password`, `created_at`, `token`)
          VALUES
          ('" . $email . "', '" . $passw . "', " . time() . ", '" . $token . "')");

        $link = APP_BASE . 'worker.php?token=' . $token;
        $link = '<a href="' . $link . '">click</a>';

        mail(
            $email,
            'confirmation link',
            $link,
            "From: Uploader <mgordo@live.com>\r\n"
        );

        Session::getInstance()->pushMessage('Please check you email and click the confirmation link');
        return;
    }

    public function token($token)
    {
        $user = Db::getInstance()->getOne(
            "SELECT * FROM `user` WHERE `active` = FALSE AND `is_deleted` = FALSE AND `token` = '" . $token . "' LIMIT 1"
        );

        if (!$user) {
            Session::getInstance()->pushMessage('Token not found');
            return;
        }

        Db::getInstance()->query(
            "UPDATE `user` SET `token` = '', `active` = TRUE WHERE `id` = " . $user->id
        );

        Session::getInstance()->setUser($user->id);
        return;
    }

    public function logout()
    {
        Session::getInstance()->forget();
    }

    /**
     * Returns set of objects representing user's pictures
     *
     * @param $userId
     * @return array
     */
    public static function loadUserPics($userId)
    {
        $pictures = Db::getInstance()->getMany(
            "SELECT * FROM `picture` WHERE `user_id` = " . (int)$userId . " ORDER BY `created_at` DESC"
        );

        if (!$pictures) {
            return [];
        }

        $set = [];

        foreach ($pictures as $picture) {
            $set[] = new Picture($picture);
        }

        return $set;
    }

    /**
     * Returns set of all active users
     *
     * @return array
     */
    public static function loadUsers($onlyActive = true)
    {
        $users = Db::getInstance()->getMany(
            "SELECT * FROM `user` " . ($onlyActive ? "WHERE `active` = TRUE AND `is_deleted` = FALSE" : "") . " ORDER BY `email`"
        );

        if (!$users) {
            return [];
        }

        $set = [];

        foreach ($users as $user) {
            $set[] = new User($user);
        }

        return $set;
    }

    /**
     * Performs search
     *
     * @param $search_string
     * @param $search_user
     * @return array
     */
    public static function loadSearchPics($search_string, $search_user)
    {
        $where = '';
        if ($search_string || $search_user) {
            $where = [];
            if ($search_user) {
                $where[] = '`user_id` = ' . (int)$search_user;
            }

            if ($search_string) {
                $where[] = "`title` LIKE '%" . $search_string . "%'";
            }

            $where = 'WHERE ' . implode(' AND ', $where);
        }

        $pictures = Db::getInstance()->getMany(
            "SELECT `picture`.*, `user`.`email` FROM `picture`
            JOIN `user` ON `user`.`id` = `picture`.`user_id`
            " . $where . " ORDER BY `created_at` DESC"
        );

        if (!$pictures) {
            return [];
        }

        $set = [];

        foreach ($pictures as $picture) {
            $set[] = new Picture($picture);
        }

        return $set;
    }

    /**
     * Delete picture belonging to current user
     * @param $id
     */
    public function deletePicture($id, $userId = 0)
    {
        Db::getInstance()->query(
            "DELETE FROM `picture` WHERE " . ($userId ? "`user_id` = " . (int)$userId . " AND" : "") . " `id` = " . (int)$id
        );
    }

    public function activateUser($userId)
    {
        $user = Db::getInstance()->getOne(
            'SELECT * FROM `user` WHERE id = ' . $userId
        );
        $user = new User($user);

        if (!$user->active) {
            Db::getInstance()->query(
                'UPDATE `user` SET `active` = TRUE WHERE `id` = ' . $userId
            );
        } else {
            Db::getInstance()->query(
                'UPDATE `user` SET `active` = FALSE WHERE `id` = ' . $userId
            );
        }
    }

    public function deleteUser($userId)
    {
        $user = Db::getInstance()->getOne(
            'SELECT * FROM `user` WHERE id = ' . $userId
        );
        $user = new User($user);

        if (!$user->is_deleted) {
            Db::getInstance()->query(
                'UPDATE `user` SET `is_deleted` = TRUE WHERE `id` = ' . $userId
            );
        } else {
            Db::getInstance()->query(
                'UPDATE `user` SET `is_deleted` = FALSE WHERE `id` = ' . $userId
            );
        }
    }

    public function upload($user)
    {
        $origName = basename($_FILES['picture']['name']);

        $ext = explode('.', $origName);
        $ext = $ext[count($ext) - 1];

        if (strpos($origName, '.') === false || strtolower($ext) != 'jpg') {
            Session::getInstance()->pushMessage('Incorrect filetype, only jpeg accepted');
            return;
        }

        $name   = Picture::generateName();
        $target = APP_UPLOAD . $name;
        $title  = $_POST['title'];

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $target)) {
            Db::getInstance()->query(
                "INSERT INTO `picture`
                  (`user_id`, `title`, `name`, `created_at`)
                  VALUES
                  (" . $user->id . ", '" . $title . "', '" . $name . "', " . time() . ")"
            );
        } else {
            Session::getInstance()->pushMessage('Uploading failed');
            return;
        }
    }
} 