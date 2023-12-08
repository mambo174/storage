<?php


require './vendor/autoload.php';

class Admin
{

    private static $connection = null;
    public static $email, $password, $role, $name, $id;

    public function __construct($connection, $email, $password, $role, $name, $id)
    {
        $dbinfo = require './config/db.php';
        self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage;charset=utf8", 'root', 'Dkflbvbh2011');
        // self::$connection = new PDO('mysql:host=' . $dbinfo['host'] . ';dbname=' . $dbinfo['dbname'] . ';charset=' . $dbinfo['charset'], $dbinfo['login'], $dbinfo['password']);
        self::$email = $email;
        self::$password = $password;
        self::$role = $role;
        self::$name = $name;
        self::$id = $id;
    }


    public static function getConnect()
    {
        if (self::$connection === null) {
            try {
                $dbinfo = require './config/db.php';
                self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage;charset=utf8", 'root', 'Dkflbvbh2011');
                // self::$connection = new PDO('mysql:host=' . $dbinfo['host'] . ';dbname=' . $dbinfo['dbname'] . ';charset=' . $dbinfo['charset'], $dbinfo['login'], $dbinfo['password']);
            } catch (\PDOException $exception) {
                exit;
            }
        }
        return self::$connection;
    }
    public static function check()
    {
        if ($_SESSION['email'] == '' && $_SESSION['password'] == '') {
            header('Location: /');
            exit;
        }
    }

    public static function adminRole($email)
    {
        $admin = self::getConnect()->prepare('SELECT role FROM user where email = :email');
        $admin->execute(['email' => $email]);
        $roles = $admin->fetch(PDO::FETCH_ASSOC);
        foreach ($roles as $role) {
            self::$role = $role;
            return $role;
        }
        //
    }

    public static function showUsers()
    {
        self::check();
        if (self::$role == 'Administrator') {
            $listData = self::getConnect()->prepare("SELECT * FROM user");
            $listData->execute();
            $arrayAll = $listData->fetchAll(PDO::FETCH_ASSOC);
            return $arrayAll;
        } elseif (self::adminRole()->role == 'User') {
            echo User::listUsers();
        } else {
            echo "Доступ в систему вам запрещен";
        }
    }

    public static function showUsersId($id)
    {
        self::check();
        $listData = self::getConnect()->prepare("SELECT email, role, name, date_created FROM user where id=:id");
        $listData->execute(['id' => $id]);
        $arrayAll = $listData->fetchAll(PDO::FETCH_ASSOC);
        return $arrayAll;
    }

    public static function destroyUserId($id)
    {
        self::check();
        if (self::adminRole()->role == 'Administrator') {
            echo 'Учетную запись Администратора удалить нельзя';
        } else {
            $deleteUser = self::getConnect()->prepare("DELETE FROM `user` WHERE id=:id");
            try {
                $deleteUser->execute([':id' => $id]);
            } catch (\Exception $e) {
                self::getConnect()->rollBack();
            }
        }
    }
    public static function updateUser()
    {
        self::check();
        if (self::adminRole()->role == 'Administrator') {
            echo 'У вас нет прав на данное действие';
        } else {
            $name = $_COOKIE['name'];
            $email = $_COOKIE['email'];
            $password = $_COOKIE['password'];
            $role = $_COOKIE['role'];
            parse_str(file_get_contents('php://input'), $PUT);
            // $email = $PUT['email'];
            // $role = $PUT['role'];
            // $name = $PUT['name'];
            $id = $PUT['id'];
            $updateData = self::getConnect()->prepare("Update user set email = :email, role = :role, name = :name WHERE id = :id");
            try {
                $updateData->execute(['email' => $email, 'role' => $role, 'name' => $name, 'id' => $id]);
            } catch (\Exception $e) {
                self::getConnect()->rollBack();
            }
        }
    }
}
