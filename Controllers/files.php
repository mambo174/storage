<?php

require './vendor/autoload.php';

class FilesStorage
{
    private static $connection = null;

    public function __construct($connection, $email, $password, $role, $name, $id)
    {
        $dbinfo = require './config/db.php';
        // self::$connection = new PDO("mysql:host=localhost;dbname=cloud_storage;charset=utf8", 'root', 'Dkflbvbh2011');
        self::$connection = new PDO('mysql:host=' . $dbinfo['host'] . ';dbname=' . $dbinfo['dbname'] . ';charset=' . $dbinfo['charset'], $dbinfo['login'], $dbinfo['password']);
        //        self::$email = $email;
        //        self::$password = $password;
        //        self::$role = $role;
        //        self::$name = $name;
        //        self::$id = $id;
    }

    public static function getConnect()
    {
        if (self::$connection === null) {
            try {
                $dbinfo = require './config/db.php';
                // self::$connection = new PDO("mysql:host=localhost;dbname=cloude_storage;charset=utf8", 'root', 'Dkflbvbh2011');
                self::$connection = new PDO('mysql:host=' . $dbinfo['host'] . ';dbname=' . $dbinfo['dbname'] . ';charset=' . $dbinfo['charset'], $dbinfo['login'], $dbinfo['password']);
            } catch (\PDOException $exception) {
                exit;
            }
        }
        return self::$connection;
    }

    public static function listFiles($dirNum)
    {
        $user = User::profile();
        foreach ($user as $keyAll => $data) {
            $user_id = $data['id'];
        }

        $listUserFiles = self::getConnect()->prepare("SELECT * FROM cloude_storage.files where user_id = :user_id and id_family=:dirNum");
        try {
            $listUserFiles->execute(['user_id' => $user_id, 'dirNum' => $dirNum]);
        } catch (\Exception $e) {
            self::getConnect()->rollBack();
        }

        $arrayLink = $listUserFiles->fetchAll(PDO::FETCH_ASSOC);
        return $arrayLink;
    }

    public static function addFile()
    {
        if (isset($_FILES['formFile'])) {
            $user = User::profile();
            foreach ($user as $keyAll => $data) {
                $user_id = $data['id'];
            }
            $target_path = './files/';
            //            $target_path = $target_path . basename($_FILES['formFile']['name']);


            $size = $_FILES['formFile']['size'];
            $fileName = explode(".", $_FILES['formFile']['name']);
            $extension = end($fileName);
            do {
                $newName = md5(microtime() . rand(0, 9999));
                $file = $newName . '.' . $extension;
            } while (file_exists($file));

            $target_path = $target_path . $file;

            $link = '/files/' . $file;
            $linkUrl = $_SERVER['HTTP_REFERER'];
            if (stristr($_SERVER['HTTP_REFERER'], "/file") == "/file") {
                $id_family = '0';
            } else {
                $id_family = preg_replace("/[^0-9]/", '', $_SERVER['HTTP_REFERER']);
            }


            $added = self::getConnect()->prepare("INSERT INTO `files`(`id`, `id_family`,`name_files`, `user_id`, `date_created`,`size`,`extension`,`link`, `new_name_files`) VALUES (null,:id_family,:name_files,:user_id,:date_created,:size,:extension,:link,:new_name_files)");
            try {
                $added->execute(['id_family' => $id_family, 'new_name_files' => $file, 'name_files' => $_FILES['formFile']['name'], 'user_id' => $user_id, 'date_created' => (new \DateTime())->format('Y-m-d H:i:s'), 'size' => $size, 'extension' => $extension, 'link' => $link]);
                move_uploaded_file($_FILES['formFile']['tmp_name'], $target_path);
            } catch (\Exception $e) {
                self::getConnect()->rollBack();
            }
        }
        // require('view/users_list.php');
        if (stristr($_SERVER['HTTP_REFERER'], "/file") == "/file") {
            header('Location: /file');
        } else {
            $id_family = preg_replace("/[^0-9]/", '', $_SERVER['HTTP_REFERER']);
            header("Location: /directory/$id_family");
        }
    }

    public static function addDirectory()
    {

        $home = $_SERVER['DOCUMENT_ROOT'];
        $dir = './files/';
        if (file_exists($dir . $_POST['add_dir'])) {
            echo "Такая папку существует измените имя";
        } else {
            $user = User::profile();
            foreach ($user as $keyAll => $data) {
                $user_id = $data['id'];
            }
            $link = '/files/' . $_POST['add_dir'];
            if ($_SERVER['REDIRECT_URL'] == '/directory') {
                $id_family = '0';
            } else {
            }
            $addDir = self::getConnect()->prepare("INSERT INTO `files`(`id`, `id_family`,`name_files`, `user_id`, `date_created`,`size`,`extension`,`link`,`new_name_files`) VALUES (null,:id_family,:name_files,:user_id,:date_created,:size,:extension,:link,:new_name_files)");
            try {
                $addDir->execute(['id_family' => $id_family, 'new_name_files' => $_POST['add_dir'], 'name_files' => $_POST['add_dir'], 'user_id' => $user_id, 'date_created' => (new \DateTime())->format('Y-m-d H:i:s'), 'size' => '0', 'extension' => '.', 'link' => $link]);
            } catch (\Exception $e) {
                self::getConnect()->rollBack();
            }
        }
        if (!isset($_POST['add_dir']) or !isset($_POST['formFile'])) {
            $_POST['add_dir'] = "";
            $_POST['formFile'] = "";
        }
        header('Location: /file');
    }

    public static function renameDirectory($id)
    {
    }

    public static function deleteDirectory($id)
    {
    }

    public static function renameFile($id, $name)
    {
        $updateFile = self::getConnect()->prepare("UPDATE files SET name_files = :name WHERE id = :id");
        try {
            $updateFile->execute(['id' => $id, 'name' => $name]);
        } catch (\Exception $e) {
            self::getConnect()->rollBack();
        }

        $updateFile->fetch(PDO::FETCH_ASSOC);
        header('Location: /file');
    }

    public static function deleteFile($id)
    {
        $delFile = self::getConnect()->prepare("DELETE FROM files WHERE files.id  = :id");
        try {
            $delFile->execute(['id' => $id]);
        } catch (\Exception $e) {
            self::getConnect()->rollBack();
        }

        $delFile->fetch(PDO::FETCH_ASSOC);

        // require('./view/list_files.php');
    }

    public static function downloadFile($files)
    {
    }

    public static function showDataFile($id)
    {
        $showDataFile = self::getConnect()->prepare("SELECT * FROM cloude_storage.files where id = :id");
        try {
            $showDataFile->execute(['id' => $id]);
        } catch (\Exception $e) {
            self::getConnect()->rollBack();
        }

        $dataFile = $showDataFile->fetch(PDO::FETCH_ASSOC);
        // $nameFile = 'file' + $id + '.txt';
        // file_put_contents($nameFile, $dataFile);
        // foreach ($dataFile as $keyAll => $data) {
        //     return $_GET[$data['name_files']];
        // }
        return $dataFile;
    }
}
