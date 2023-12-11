<?php
include_once './Controllers/User.php';
include_once './Controllers/Admin.php';

$role = Admin::adminRole($_SESSION['email']);
Admin::check();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Добро пожаловать в облачное хранилище учебного заведения</title>
    <link href="../style/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">


    <link href="/style/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1 class="text-center">Добро пожаловать в хранилище</h1>
    <ul class="nav justify-content-center">
        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
        <?php
        if ($role == 'Administrator') {
            echo '<li class="nav-item"><a class="nav-link" href="/admin/user">Список пользователей</a></li>';
        } elseif ($role == 'User') {
            echo '<li class="nav-item"><a class="nav-link" href="/user">Список пользователей</a></li>';
        }
        ?>
        <li class="nav-item"><a class="nav-link" href="/file">Список файлов</a></li>
    </ul>
    <div class="container col-lg-9">
        <div class="row align-items-start">
            <div class="col-lg-2">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>Профиль</span>
                    </div>
                    <div class="panel-body">
                        <form action="/logout" method="get">
                            <ul class="list-group">
                                <?php
                                $profileData = User::profile();
                                foreach ($profileData as $keyAll => $data) {
                                    $email = $data['email'];
                                    $name = $data['name'];
                                    $role = $data['role'];
                                    $createDate = $data['date_created'];
                                    echo "
                    <li class='list-group-item profile'><span> Имя: </span>" . $name . "
                    <li class='list-group-item profile'><span> Регистрации: </span>" . $createDate . "
                    <li class='list-group-item profile'><span> Роль: </span>" . $role . "";
                                }
                                ?>

                            </ul>
                            <div class="float-right mt-2">
                                <button class="btn btn-primary btn-sm" type="submit" name=\"/logout"\ value="Выйти">Выйти</button>
                            </div>
                    </div>
                    </form>
                </div>

            </div>
            <div class="col" style="width: 85%; text-align: center"></div>
        </div>
    </div>

</body>

</html>