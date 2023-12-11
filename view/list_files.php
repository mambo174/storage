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
    <link href="../style/bootstrap.css" rel="stylesheet">
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
    <div class="container col-lg-10">
        <div class="row row-new align-items-start">
            <div class="col-lg-2">
                <button class="btn_open btn btn-primary col-lg-12 mb-3 " id="closeModal">+</button>
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

            <div class="modal-editor">
                <div class="modal-editor_content">
                    <span class="btn_close">&times</span>
                    <form method="put" class='form-content_editor'>



                    </form>
                </div>
            </div>

            <div class="modal">
                <div class="modal-content col-6">
                    <span class="btn_close float-right">&times</span>
                    <div class="mb-4">

                        <form method="POST" action="/file" class="col" id="formFilegroup" style="width: 85%; text-align: left;" enctype="multipart/form-data">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Загрузить файл</span>
                                </div>

                                <div class="custom-file">
                                    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="2000000" /> -->
                                    <input class="custom-file-input" type="file" name="formFile" id="formFile" aria-describedby="formFilegroup" value="Обзор" />
                                    <label class="custom-file-label" for="formFile">Выберите файл</label>
                                </div>
                                <div class="input-group-append">
                                    <input class="btn btn-outline-secondary" type="submit" id="formFilegroup" name="submit" value="Загрузить">
                                </div>

                            </div>


                        </form>
                    </div>

                    <div>

                        <form action="/directory" method="post" id="formFile">
                            <div class="input-group mb-4 margin-left">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Создать папку</span>
                                </div>
                                <input type="text" class="form-control col-6" maxlength="20" name="add_dir" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <input class="btn btn-outline-secondary" role="button" type="submit" id="button-addon2" value="Создать папку">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="file">
                <div class="info-file">
                    <span class="btn_close">&times</span>


                </div>
                <div class="edit-file"></div>
            </div>
            <div class="row col-lg-9">
                <div id="top-panel ">
                    <div id="panel">
                        <div class="panel-btn">
                            <button class="btn-info" type='button' name='all' value='' onclick="info()" /></button>
                        </div>
                        <div class="panel-btn"><button class="btn-edit" type='submit' name='all' value='' onclick="edit()"></button></div>
                        <div class="panel-btn"><button class="btn-trash" type='submit' name='all' value='' onclick="del()"></button></div>
                        <!-- <div id="panel-editor">
                            <button class="btn-apply" type='submit' name='all' value='' onclick="safe()"></button>
                            <button class="btn-reload" type='submit' name='all' value='' onclick="location.reload(); return false;"></button>
                        </div> -->
                    </div>
                </div>
                <div class="list-file col-lg-12 position-static">

                    <ul class="list-group mt-10">

                        <?php
                        $dirNum = str_replace('directory/', '', $_GET['id']);
                        if ($dirNum > 0 and is_numeric($dirNum)) {
                            $listFiles = FilesStorage::listFiles($dirNum);
                            echo "<li class='list-group-item'><a href='/file'>    ...</a></li>";

                            foreach ($listFiles as $keyAll => $data) {


                                if (preg_match("/[.][a-zA-Z1-9]/u", $data['name_files'])) {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item pl-5'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                } else {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'> " . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value=''></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item pl-5'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'> " . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value=''></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                }
                            }
                        } else {

                            $listFiles = FilesStorage::listFiles(0);

                            foreach ($listFiles as $keyAll => $data) {

                                if (preg_match("/[.][a-zA-Z1-9]/u", $data['name_files'])) {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item pl-5'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
                                } else {
                                    // echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                    echo "<div id='label'><li class='list-group-item pl-5'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div>";
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