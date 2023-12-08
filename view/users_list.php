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
<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/style.css">
    <script src="../js/script.js" type="text/javascript">

    </script>
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
            <div class="modal-editor">
                <div class="modal-editor_content">
                    <span class="btn_close">&times</span>
                    <form method="put" class='form-content_editor'>
                        <!-- <input type="hidden" name="_method" value="PUT"> -->
                        <!-- <input type="submit" role="button" name="submit" value="Сохранить" onclick="safeUser()"> -->

                    </form>
                </div>
            </div>
            <div class="col" style="width: 85%; text-align: center">
                <h3>Список пользователей.</h3>
                <!-- <div class="row"> -->
                <div>
                    <div id="top-panel">
                        <div id="panel">
                            <div class="panel-btn"><button class="btn-edit" type='submit' name='all' value='' onclick="editUser()"></button></div>
                            <div class="panel-btn"><button class="btn-trash" type='submit' name='all' value='' onclick="del()"></button></div>
                        </div>
                    </div>
                    <div class="list-user">
                        <ul class="user_list_header">
                            <li>Выбрать</li>
                            <li>e-mail</li>
                            <li>Имя</li>
                            <li>Роль</li>
                            <li>Дата создания</li>
                        </ul>
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
                                    $role = $userData[$i]['role'];
                                    // echo "<form method='PUT' action='/admin/user/" . $id . "'><div id='label' ><ul class='user_list' ><li><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $id . "'></li><li>" . $email . "</li><li>" . $name . "</li><li>" . $createDate . "</li></ul></div></form>";
                                    echo "<div id='label' ><ul class='user_list' ><li><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $id . "'></li><li id='email'>" . $email . "</li><li id='name'>" . $name . "</li><li id='role'>" . $role . "</li><li id='createDate'>" . $createDate . "</li></ul></div>";

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
                                    $role = $userData[$i]['role'];
                                    echo "<ul class='user_list'><li>" . $id . "</li><li>" . $email . "</li><li>" . $name . "</li><li id='name'>" . $role . "</li><li>" . $createDate . "</li></ul>";

                                    $i++;
                                }
                            }
                        }
                        ?>
                        <!-- </table> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>