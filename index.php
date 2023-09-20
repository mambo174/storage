<?php
session_start();
require_once './Controllers/User.php';
require_once './Controllers/Admin.php';
require_once './Controllers/files.php';
$_SESSION['id'] = session_id();

if ($_SERVER['REQUEST_URI'] == '/') {
    if (isset($_SESSION['email'])) {
        User::login();
        exit;
    } else {
        require('view/login.html');
        exit;
    }
}

if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
    if (!empty($_GET['email']) && !empty($_GET['password'])) {
        $_SESSION['email'] = $_GET['email'];
        $_SESSION['password'] = $_GET['password'];
    } else {
        header('Location: /');
    }
} else {
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
}


$urlList = [
    '/user' => ['GET' => 'User::listUsers', 'POST' => 'User::addUser', 'PUT' => 'User::updateUser'],
    '/user/{id}' => ['GET' => 'User::showUser', 'DELETE' => 'User::destroyUser'],
    '/login' => ['GET' => 'User::login'],
    '/logout' => ['GET' => 'User::logout'],
    '/reset_password' => ['GET' => 'User::reset_password'],
    '/reset_password/{hash}' => ['GET' => 'User::newPassword'],
    '/admin/user' => ['GET' => 'Admin::showUsers', 'PUT' => 'Admin::updateUser'],
    '/admin/user/{id}' => ['GET' => 'Admin::showUsersId', 'DELETE' => 'Admin::destroyUserId'],
    '/file' => ['GET' => 'FilesStorage::listFiles', 'POST' => 'FilesStorage::addFile'],
    '/file/{id}' => ['GET' => 'FilesStorage::showDataFile', 'DELETE' => 'FilesStorage::destroyFile', 'PUT' => 'FilesStorage::renameFile'],
    '/directory' => ['POST' => 'FilesStorage::addDirectory', 'PATCH' => 'FilesStorage::renameDirectory'],
    '/directory/{id}' => ['PUT' => 'FilesStorage::showDataDirectory', 'DELETE' => 'FilesStorage::deleteDirectory']
];

$id = trim($_GET['id'], 'user/');

$hash = str_replace('reset_password/', '', $_GET['id']);

if (preg_match("/\/user\/[1-9]/u", $_SERVER['REDIRECT_URL'])) {
    $paramUrl = '/user/{id}';
    $url = $urlList[$paramUrl];
} elseif (preg_match("/\/reset_password\/[$.a-zA-Z1-9]/u", $_SERVER['REDIRECT_URL'])) {
    $paramUrl = '/reset_password/{hash}';
    $url = $urlList[$paramUrl];
} elseif (preg_match("/\/file\/[$.a-zA-Z1-9]/u", $_SERVER['REDIRECT_URL'])) {
    $paramUrl = '/file/{id}';
    $url = $urlList[$paramUrl];
} elseif (preg_match("/\/directory\/[1-9]/u", $_SERVER['REDIRECT_URL'])) {
    $paramUrl = '/directory/{id}';
    $url = $urlList[$paramUrl];
} else {
    $paramUrl = $_SERVER['REDIRECT_URL'];
    $url = $urlList[$_SERVER['REDIRECT_URL']];
}

switch ($paramUrl) {
    case '/user':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value($id);
                // require('view/users_list.php');
            }
        }
        break;

    case '/user/{id}':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value($id);
            }
        }
        break;
    case '/login':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value();
            }
        }
        break;
    case '/logout':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value();
            }
        }
        break;
    case '/reset_password':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value();
            }
        }
        break;
    case '/reset_password/{hash}':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value($hash);
            }
        }
        break;
    case '/admin/user':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                //                session_start();
                require('view/users_list.php');
                //                $value();
            }
        }
        break;

    case '/file':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                //                $value();
                require('view/list_files.php');
                exit;
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // $value;
                FilesStorage::addFile();
                exit;
            }
        }
        break;

    case '/file/{id}':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $id = preg_replace("/[^0-9]/", '', $id);
                // FilesStorage::showDataFile($id);

                require('view/infoFile.php');
                exit;
            } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $id = preg_replace("/[^0-9]/", '', $id);
                FilesStorage::deleteFile($id);
                // exit;
            } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                $id = preg_replace("/[^0-9]/", '', $id);
                $name = $_COOKIE['nameFile'];

                FilesStorage::renameFile($id, $name);
            }
        }
        break;

    case '/directory':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == $key) {
                $value();
                exit;
            }
        }

    case '/directory/{id}':
        foreach ($url as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                require('view/list_files.php');
                exit;
                // $value();
            } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                FilesStorage::renameDirectory($id);
                exit;
            }
        }
}
