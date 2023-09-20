<?php

require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class User
{
    private static $connection = null;
    public $email, $password, $role, $name, $id;

    public function __construct($connection, $email, $password, $role, $name, $id)
    {
        $dbinfo = require './config/db.php';
        // self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage;charset=utf8", 'root', 'Dkflbvbh2011');
        // self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage", 'root', 'Dkflbvbh2011');
        self::$connection = new PDO('mysql:host=' . $dbinfo['host'] . ';dbname=' . $dbinfo['dbname'] . ';charset=' . $dbinfo['charset'], $dbinfo['login'], $dbinfo['password']);
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->name = $name;
    }

    public static function getConnect()
    {
        if (self::$connection === null) {
            try {
                $dbinfo = require './config/db.php';
                // self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage;charset=utf8", 'root', 'Dkflbvbh2011');
                // self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage", 'root', 'Dkflbvbh2011');
                self::$connection = new PDO('mysql:host=' . $dbinfo['host'] . ';dbname=' . $dbinfo['dbname'] . ';charset=' . $dbinfo['charset'], $dbinfo['login'], $dbinfo['password']);
            } catch (\PDOException $exception) {
                $exception->getMessage();
            }
        }

        return self::$connection;
    }

    public static function profile()
    {
        Admin::check();
        $profileData = self::getConnect()->prepare("SELECT id, email, name,DATE_FORMAT( `date_created` , '%d-%m-%Y' ) AS `date_created`,role FROM user where email=:email");
        $profileData->execute(['email' => $_SESSION['email']]);
        $arrayProfile = $profileData->fetchAll(PDO::FETCH_ASSOC);
        return $arrayProfile;
    }

    public static function listUsers()
    {
        Admin::check();
        $listData = self::getConnect()->prepare("SELECT id,email, name,date_created FROM user");
        $listData->execute();
        $arrayAll = $listData->fetchAll(PDO::FETCH_ASSOC);
        return $arrayAll;
    }

    public static function showUser($id)
    {
        Admin::check();
        $listData = self::getConnect()->prepare("SELECT email, role, name, date_created FROM user where id=:id");
        $listData->execute(['id' => $id]);
        $arrayAll = $listData->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($arrayAll);
    }

    public static function addUser()
    {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $name = $_POST['name'];
        $insertData = self::getConnect()->prepare("INSERT INTO `user`(`id`, `email`, `password`, `role`, `name`, `date_created`) VALUES (null,:email,:password,:role,:name,:date_created)");
        try {
            $insertData->execute(['email' => $email, 'password' => $password, 'role' => $role, 'name' => $name, 'date_created' => (new \DateTime())->format('Y-m-d H:i:s')]);
        } catch (\Exception $e) {
            // self::getConnect()->rollBack();
        }
    }


    public static function updateUser()
    {
        Admin::check();
        parse_str(file_get_contents('php://input'), $PUT);
        $email = $PUT['email'];
        $role = $PUT['role'];
        $name = $PUT['name'];
        $id = $PUT['id'];
        $updateData = self::getConnect()->prepare("Update user set email = :email, role = :role, name = :name WHERE id = :id");
        try {
            $updateData->execute(['email' => $email, 'role' => $role, 'name' => $name, 'id' => $id]);
        } catch (\Exception $e) {
            self::getConnect()->rollBack();
        }
    }


    public static function destroyUser($id)
    {
        Admin::check();
        $deleteUser = self::getConnect()->prepare("DELETE FROM `user` WHERE id=:id");
        try {
            $deleteUser->execute([':id' => $id]);
        } catch (\Exception $e) {
            self::getConnect()->rollBack();
        }
    }

    public static function login()
    {
        Admin::check();
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        $userLogin = self::getConnect()->prepare("Select id,email, password FROM user Where email=:email");
        $userLogin->execute([':email' => $email]);
        $user = $userLogin->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            require('view/main.php');
        } else {
            echo '<script>alert("Такой пользователь не найден")</script>';
            session_destroy();
            header('Location: /');
            exit();
        }
    }


    public static function logout()
    {
        session_destroy();
        if (session_id() == '') {
            header('Location: storage.local');
        }
    }

    public static function reset_password()
    {
        $email = $_SERVER['HTTP_EMAIL'];
        $hash = md5($email . time());
        $title = "Восстановление пароля";
        $body = '<html><head>Восстановление пароля</head><body><p>Что бы восстановить пароль перейдите по <a href="http://localhost/reset_password/' . $hash . '">ссылка</a></p></body></html>';
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = "UTF-8";
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = 4;
            $mail->Debugoutput = function ($str, $level) {
                $GLOBALS['status'][] = $str;
            };

            $mail->Host = 'smtp.yandex.ru';
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('mambo17413@yandex.ru', 'Администратор');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;
            if ($mail->send()) {
                $result = "success";
            } else {
                $result = "error";
            }
        } catch (Exception $e) {
            $result = "error";
            $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
            echo $status;
        }
        $newHash = self::getConnect()->prepare("Update user set hash=:hash WHERE email=:email");
        $newHash->execute(['hash' => $hash, 'email' => $email]);
    }

    public static function newPassword($hash)
    {
        if ($hash) {
            $identifityHash = self::getConnect()->prepare("SELECT id FROM user where hash=:hash");
            //            $hash= str_replace('"',"'",$hash);
            if ($identifityHash->execute(['hash' => $hash])) {
                while ($row = $identifityHash->fetch(PDO::FETCH_ASSOC)) {
                    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
                    $numChars = strlen($chars);
                    $pass = '';
                    for ($i = 0; $i < 8; $i++) {
                        $pass .= substr($chars, rand(1, $numChars) - 1, 1);
                    }
                    $passHash = password_hash($pass, PASSWORD_BCRYPT);
                    $newPass = self::getConnect()->prepare('UPDATE user SET password = :pass WHERE id=:id');
                    $newPass->execute(['pass' => $passHash, 'id' => $row['id']]);
                    echo "Ваш новый пароль " . $pass;
                }
            } else {
                echo "Ошибка";
            }
        } else {
            echo "Ошибка хэша";
        }
    }
}
