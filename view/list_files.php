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
    <script type="text/javascript">
        // const modal = document.getElementsByClassName('modal');

        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.querySelector('.modal');

            var openButton = document.querySelectorAll('.btn_open')
            for (let i = 0; i < openButton.length; i++) {
                openButton[i].addEventListener('click', function() {
                    modal.style.display = 'block'
                })
            }

        })
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.querySelector('.modal');

            var closeButton = document.querySelectorAll('.btn_close')
            for (let i = 0; i < closeButton.length; i++) {
                closeButton[i].addEventListener('click', function() {
                    modal.style.display = 'none'
                })
            }
        })

        document.addEventListener("DOMContentLoaded", function() {
            var file = document.querySelector('.file');

            var closeButton = document.querySelectorAll('.btn_close')
            for (let i = 0; i < closeButton.length; i++) {
                closeButton[i].addEventListener('click', function() {
                    file.style.display = 'none'
                })
            }

            var modalEditor = document.querySelector('.modal-editor')
            for (let i = 0; i < closeButton.length; i++) {
                closeButton[i].addEventListener('click', function() {
                    modalEditor.style.display = 'none'
                    modalEditor.remove();

                })
            }

        })

        function check() {
            var panel = document.querySelector('#panel');
            var checkboxes = document.querySelectorAll('input[type="checkbox"]')
            var panelEditor = document.querySelector('#panel-editor');
            for (var checkbox of checkboxes) {

                if (checkbox.checked) {
                    panel.style.display = 'flex'
                    break;
                } else {
                    panel.style.display = 'none'
                    panelEditor.style.display = 'none';
                    checkbox.parentElement.contentEditable = "false";
                }
            }
        }

        function edit() {
            var chbox;
            var panelEditor = document.querySelector('#panel-editor');
            chbox = document.getElementById('id');
            var edit = document.querySelectorAll('.btn-edit');
            // var checkboxes = document.querySelectorAll('.checkbox:checked');
            var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');



            for (var checkbox of checkboxes) {
                if (checkbox.checked) {
                    var modalEditor = document.querySelector('.modal-editor');
                    // var modalEditor = document.querySelector('modal-editor_content');
                    var modalEditorCont = document.querySelector('.form-content_editor');
                    let hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.id = 'hid';
                    hidden.value = checkbox.value;
                    modalEditorCont.appendChild(hidden);

                    let input = document.createElement('input');
                    input.value = checkbox.nextElementSibling.textContent;
                    input.id = 'nameFile';
                    input.style = 'margin-bottom: 10px;';

                    modalEditorCont.id = 'fileNameEditor'
                    modalEditorCont.style = 'display:contents'
                    // modalEditorCont.action = '/file/' + checkbox.value;
                    modalEditorCont.appendChild(input);
                    let button = document.createElement('input');
                    button.type = 'submit';
                    button.role = 'button';
                    button.name = 'submit';
                    button.setAttribute("onclick", "safe()")
                    button.value = 'Применить';
                    modalEditorCont.appendChild(button);

                    modalEditor.style.display = 'flex';
                }
            }

        }

        function safe() {

            // chbox = document.getElementById('id');
            var info = document.getElementById('nameFile');
            var hidden = document.getElementById('hid');
            // var checkboxes = document.querySelectorAll('input[type="checkbox"]')
            document.cookie = "nameFile=" + info.value;


            // if (checkbox.checked) {
            fetch('/file/' + hidden.value, {
                method: 'PUT'
            })




            // window.location.reload(1)


        }

        function logout() {
            fetch('/logout', {
                method: 'GET'
            })
            window.location.reload(1)
        }

        function del() {
            var chbox;
            chbox = document.getElementById('id');
            // var file = document.querySelector('.file')
            var info = document.querySelectorAll('.btn-trash');
            var checkboxes = document.querySelectorAll('input[type="checkbox"]')

            for (var checkbox of checkboxes) {
                // for (i = 0; i < checkboxes.length; i++) {

                if (checkbox.checked) {

                    // window.open('/file/' + checkbox.value, '_blank').focus
                    fetch('/file/' + checkbox.value, {
                        method: 'DELETE',
                    })
                }

            }
            window.location.reload(1)

        }

        function info() {
            var chbox;
            chbox = document.getElementById('id');
            var file = document.querySelector('.file')
            var info = document.querySelectorAll('.btn-info');
            var checkboxes = document.querySelectorAll('input[type="checkbox"]')

            for (var checkbox of checkboxes) {

                if (checkbox.checked) {
                    // for (let i = 0; i < info.length; i++) {
                    // info[i].addEventListener('click', function() {
                    // file.style.display = 'block'
                    // let responce = await fetch(checkbox.value)
                    // let commits = await responce.json()
                    // console.log(commits[0])

                    // fetch(checkbox.value)
                    // .then(res => res.json())
                    // .then(data => console.log(data))

                    // let form = document.createElement('form')
                    // form.method = "GET"
                    // form.action = checkbox.value
                    window.open('/file/' + checkbox.value, '_blank').focus()
                    // document.location = '/view/infoFile.php?id=' + checkbox.value
                    console.log(checkbox.value)

                    // form.innerHTML = startPHP + ''
                    // document.querySelector('.info-file').append(form)


                    // form.submit();
                    // })
                    // }
                }
            }
        }
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
                        <!-- <input type="hidden" name="_method" value="PUT"> -->
                        <!-- <input type="submit" role="button" name="submit" value="Сохранить" onclick="safe()"> -->

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
                <div clas="list-file">

                    <ul class="list-group">

                        <?php
                        $dirNum = str_replace('directory/', '', $_GET['id']);
                        if ($dirNum > 0 and is_numeric($dirNum)) {
                            $listFiles = FilesStorage::listFiles($dirNum);
                            echo "<li class='list-group-item'><a href='/file'>    ...</a></li>";

                            foreach ($listFiles as $keyAll => $data) {


                                if (preg_match("/[.][a-zA-Z1-9]/u", $data['name_files'])) {
                                    echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                } else {
                                    echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'> " . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value=''></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                }
                            }
                        } else {

                            $listFiles = FilesStorage::listFiles(0);

                            foreach ($listFiles as $keyAll => $data) {

                                if (preg_match("/[.][a-zA-Z1-9]/u", $data['name_files'])) {
                                    echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='./files/" . $data['new_name_files'] . "' download='" . $data['name_files'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
                                } else {
                                    echo "<form method='PUT' action='/file/" . $data['id'] . "'><div id='label'><li class='list-group-item'><p id='editP' contenteditable='false'><input  class='form-check-input me-1' type='checkbox' id='id' onclick='check();' name='fileId' value='" . $data['id'] . "'><a href='/directory/" . $data['id'] . "'>" . $data['name_files'] . "</a></p></li><div id='panel-editor'><button class='btn-apply' type='submit' name='all' value='' ></button><button class='btn-reload' type='submit' name='all' value='' onclick='location.reload(); return false;'></button></div></div></form>";
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