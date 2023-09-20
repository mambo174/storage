<?php
include_once './Controllers/User.php';
include_once './Controllers/Admin.php';
//$users = new User();
// Admin::check();
$role = Admin::adminRole($_SESSION['email']);

if ($role == 'Administrator') {
    $userData = Admin::showUsers();
} elseif ($role == 'User') {
    $userData = User::listUsers();
}


function countArrays(array $userData)
{
    $count = 0;
    foreach ($userData as $key => $value) {
        if (is_array($value)) {
            $count += countArrays($value) + 1;
        }
    }
    return $count;
}

?>
<html>

<head>
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
                                $id = $data['id'];
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
                    </form>
                </div>
            </div>
            <div class="col" style="width: 85%; text-align: center">
                <h3>Список пользователей.</h3>
                <form method='post'>
                    <table width="80%" align="center">
                        <tr>
                            <td>Номер</td>
                            <td>Имя</td>
                            <td>e-mail</td>
                            <td>Дата создания</td>
                        </tr>
                        <?php
                        $list = array();
                        $i = 0;
                        if ($role == 'Administrator') {
                            while ($i < countArrays($userData)) {
                                foreach ($userData as $keyAll => $data) {
                                    $id = $userData[$i]['id'];
                                    $email = $userData[$i]['email'];
                                    $name = $userData[$i]['name'];
                                    $createDate = $userData[$i]['date_created'];
                                    echo "
                        <tr>
                        
                            <td><input type=\"text\" name=\"id\" value=" . $id . "></td>
                            <td><input type=\"text\" name=\"editName\" value=" . $name . "></td>
                            <td><input type=\"text\" name=\"editEmail\"value=" . $email . "></td>
                            <td><input type=\"text\" name=\"editCreateDate\"value=" . $createDate . "></td>
                            <td><input type=\"submit\" name=\"/admin/user/" . $id . "\" value=\"Редактировать\"><input type=\"submit\" name=\"/admin/user/" . $id . "\" value=\"Удалить\"></td>                                                      
                            </form>
                        </tr>";
                                    $i++;
                                }
                            }
                        } elseif ($role == 'User') {
                            while ($i < countArrays($userData)) {
                                foreach ($userData as $keyAll => $data) {
                                    $id = $userData[$i]['id'];
                                    $email = $userData[$i]['email'];
                                    $name = $userData[$i]['name'];
                                    $createDate = $userData[$i]['date_created'];
                                    echo "
                        <tr>
                        <td>" . $id . "</td>
                        <td>" . $name  . "</td>
                        <td>" . $email . "</td>
                        <td>" . $createDate . "</td>                        
                        </tr>";
                                    $i++;
                                }
                            }
                        }
                        ?>
                    </table>
            </div>
        </div>
    </div>
</body>

</html>