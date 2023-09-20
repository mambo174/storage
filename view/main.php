<?php
include_once './Controllers/User.php';
include_once './Controllers/Admin.php';

$role = Admin::adminRole($_SESSION['email']);
Admin::check();
?>
<html>

<head>
    <title>Добро пожаловать в облачное хранилище учебного заведения</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
    <div class="container">
        <div class="row align-items-start">
            <div class="col-4" style="width: 15%;">
                <div style=" text-align: center;">
                    <h3>Профиль</h3>
                </div>
                <div style="text-align: center">
                    <form action="/logout" method="get">
                        <table>
                            <?php
                            $profileData = User::profile();
                            foreach ($profileData as $keyAll => $data) {
                                $email = $data['email'];
                                $name = $data['name'];
                                $role = $data['role'];
                                $createDate = $data['date_created'];
                                echo "
                    <tr>
                        <td>Имя</td><td>" . $name . "</td>
                    </tr>
                    <tr>
                        <td>Регистрации</td><td>" . $createDate . "</td>
                    </tr>
                    <tr>
                        <td>Роль</td><td>" . $role . "</td>
                    </tr>";
                            }
                            ?>

                        </table>
                        <input type="submit" name=\"/logout"\ value="Выйти">
                </div>
                </form>

            </div>
            <div class="col" style="width: 85%; text-align: center"></div>
        </div>
    </div>

</body>

</html>