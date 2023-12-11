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
    <link href="../style/bootstrap.css" rel="stylesheet">
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
    <div class="container col-lg-10">
        <div class="row align-items-start">
            <div class="col-lg-2">
                <button class="btn_open btn btn-primary col-lg-12 mb-3 " id="closeModal">Добавить пользователя</button>
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

            <div class="modal">
                <div class="modal-content col-4">
                    <span class="btn_close">&times</span>
                    <div class="mb-4">

                        <form method="POST" action="/user" class="col" id="formFilegroup" style="width: 85%; text-align: left;" enctype="multipart/form-data">
                            <div class="form-group row">
                                <span class="text-center mb-4">Добавить пользователя</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text col-12" id="formNewUser1">Email</span>
                                    </div>
                                    <input class="form-control" type="email" name="new_email" id="formNewUser1" />
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text " id="formNewUser2">Логин</span>
                                    </div>
                                    <input class="form-control" type="text" name="new_login" id="formNewUser2" />
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="formNewUser4">Пароль</span>
                                    </div>
                                    <input class="form-control" type="password" name="new_pass" id="formNewUser4" />
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="formNewUser3">Роль</span>
                                    </div>
                                    <select class="custom-select" name="new_group" id="formNewUser3">
                                        <option selected>Роль</option>
                                        <option value="Admin">Администратор</option>
                                        <option value="User">Пользователь</option>
                                    </select>
                                </div>
                                <!-- <div class="custom-file"> -->

                            </div>
                            <input class="btn btn-outline-primary" role="button" type="submit" value="Добавить">


                        </form>
                    </div>

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
            <div class="col col-lg-9" style="width: 85%; text-align: center">
                <h3>Список пользователей.</h3>
                <!-- <div class="row"> -->
                <div>
                    <div id="top-panel">
                        <div id="panel">
                            <div class="panel-btn"><button class="btn-edit" type='submit' name='all' value='' onclick="editUser()"></button></div>
                            <div class="panel-btn"><button class="btn-trash" type='submit' name='all' value='' onclick="delUser()"></button></div>
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
                                    echo "<div id='label'><ul class='user_list'><li><input class='form-check-input me-1' type='checkbox' id='id' onclick='checkUser();' name='fileId' value='" . $id . "'></li><li id='email'>" . $email . "</li><li id='name'>" . $name . "</li><li id='role'>" . $role . "</li><li id='createDate'>" . $createDate . "</li></ul></div>";

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