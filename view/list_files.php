<!DOCTYPE html>
<?php
include_once './Controllers/User.php';
include_once './Controllers/Admin.php';
include_once './Controllers/files.php';

if (isset($_POST['editDir'])) {
    $id = $_POST['id'];
    FilesStorage::renameDirectory($id);
} elseif (isset($_POST['editFile'])) {
    $id = $_POST['id'];
    FilesStorage::renameFile($id);
}
if (isset($_POST['deleteDir'])) {
    $id = $_POST['id'];
    FilesStorage::deleteDirectory($id);
} elseif (isset($_POST['deleteFile'])) {
    $id = $_POST['id'];
    FilesStorage::deleteFile($id);
}


Admin::check();

?>

<html>

<head>
    <title>Список файлов - Облачное хранилище учебного заведения</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style type="text/css" media="all">
        @import url("/style/style.css");
    </style>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <meta charset="utf-8">
    <script src="../js/script.js" type="text/javascript">

    </script>
</head>

<body>

    <h1 class="text-center">Добро пожаловать в хранилище</h1>
    <ul class="nav justify-content-center">
        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
        <?php
        $role = Admin::adminRole($_SESSION['email']);
        if ($role == 'Administrator') {
            echo '<li class="nav-item"><a class="nav-link" href="/admin/user">Список пользователей</a></li>';
        } elseif ($role == 'User') {
            echo '<li class="nav-item"><a class="nav-link" href="/user">Список пользователей</a></li>';
        }
        ?>
        <li class="nav-item"><a class="nav-link" href="/file">Список файлов</a></li>
    </ul>
    <div class="container">
        <div class="row row-new align-items-start">

            <div class="col-4 profile">
                <button class="btn_open" id="closeModal">+</button>
                <div style=" text-align: center;">
                    <h3>Профиль</h3>
                </div>
                <div style="text-align: left">
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
                        <td>Регистрация</td><td>" . $createDate . "</td>
                    </tr>
                    <tr>
                        <td>Роль</td><td>" . $role . "</td>
                    </tr>";
                        }
                        ?>

                    </table>

                    <input type="submit" name=\"/logout"\ onclick="logout()" value="Выйти">
                </div>

            </div>
            <div class="modal-editor">
                <div class="modal-editor_content">
                    <span class="btn_close">&times</span>
                    <form method="put" class='form-content_editor'>



                    </form>
                </div>
            </div>

            <div class="modal">
                <div class="modal-content">
                    <span class="btn_close">&times</span>

                    <form method="POST" action="/file" class="col" id="formFile" style="width: 85%; text-align: left;" enctype="multipart/form-data">
                        <h3>Загрузить файл</h3>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                        <input class="form-control" type="file" name="formFile" id="formFile" /></p>
                        <p><input class="btn btn-primary me-md-2" type="submit" role="button" name="submit" value="Загрузить">
                        </p>
                    </form>

                    <form action="/directory" method="post" id="formFile">
                        <h5>Создать папку</h5>
                        <input style="width: 200px" type="text" name="add_dir">
                        <input class="btn btn-primary me-md-2" role="button" type="submit" value="Создать папку">
                    </form>

                </div>
            </div>
            <div class="file">
                <div class="info-file">
                    <span class="btn_close">&times</span>


                </div>
                <div class="edit-file"></div>
            </div>
            <div class="row">
                <div id="top-panel">
                    <div id="panel">
                        <div class="panel-btn">
                            <input class="btn-info" type='button' name='all' value='' onclick="info()" />
                        </div>
                        <div class="panel-btn"><button class="btn-edit" type='submit' name='all' value='' onclick="edit()"></button></div>
                        <div class="panel-btn"><button class="btn-trash" type='submit' name='all' value='' onclick="del()"></button></div>
                        <!-- <div id="panel-editor">
                            <button class="btn-apply" type='submit' name='all' value='' onclick="safe()"></button>
                            <button class="btn-reload" type='submit' name='all' value='' onclick="location.reload(); return false;"></button>
                        </div> -->
                    </div>
                </div>
                <div class="list-file">

                    <ul class="list-group">

                        <?php
                        $dirNum = str_replace('directory/', '', $_GET['id']);
                        if ($dirNum > 0 and is_numeric($dirNum)) {
                            $listFiles = FilesStorage::listFiles($dirNum);
                            echo "<li class='list-group-item'><a href='/file'>    ...</a></li>";

                            foreach ($listFiles as $keyAll => $data) {


                                if (preg_match("/[.][a-zA-Z1-9]/u", $data['name_files'])) {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                } else {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'> " . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value=''></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'> " . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value=''></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                }
                            }
                        } else {

                            $listFiles = FilesStorage::listFiles(0);

                            foreach ($listFiles as $keyAll => $data) {

                                if (preg_match("/[.][a-zA-Z1-9]/u", $data['name_files'])) {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                } else {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>


        </div>
    </div>
    </div>

</body>


</html>