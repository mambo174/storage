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

        function editUser() {
            var chbox;
            var panelEditor = document.querySelector('#panel-editor');
            chbox = document.getElementById('id');
            var edit = document.querySelectorAll('.btn-edit');
            // var checkboxes = document.querySelectorAll('.checkbox:checked');
            var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            



            for (var checkbox of checkboxes) {
                if (checkbox.checked) {
                    var modalEditor = document.querySelector('.modal-editor');
                    var liEmail = document.getElementById("email");
                    var liName = document.getElementById("name");
                    var liRole = document.getElementById("role");
                    // var liCreateDate = document.getElementById("createDate");

                    // var modalEditor = document.querySelector('modal-editor_content');
                    var modalEditorCont = document.querySelector('.form-content_editor');
                    let email = document.createElement('input');
                    email.type = 'hidden';
                    email.id = 'hid';
                    email.value = checkbox.value;
                    modalEditorCont.appendChild(email);

                    let inputEmail = document.createElement('input');
                    inputEmail.value = liEmail.textContent;
                    inputEmail.id = 'input_email';
                    inputEmail.style = 'margin-bottom: 10px;';

                    let inputName = document.createElement('input');
                    inputName.value = liName.textContent;
                    inputName.id = 'input_name';
                    inputName.style = 'margin-bottom: 10px;';

                    let inputRole = document.createElement('input');
                    inputRole.value = liRole.textContent;
                    inputRole.id = 'input_role';
                    inputRole.style = 'margin-bottom: 10px;';

                    let inputPassword = document.createElement('input');
                    // inputPassword.value = liCreateDate.textContent;
                    inputPassword.id = 'input_password';
                    inputPassword.style = 'margin-bottom: 10px;';

                    modalEditorCont.id = 'fileNameEditor'
                    modalEditorCont.style = 'display:contents'
                    // modalEditorCont.action = '/file/' + checkbox.value;
                    modalEditorCont.appendChild(inputEmail);
                    modalEditorCont.appendChild(inputName);
                    modalEditorCont.appendChild(inputPassword);
                    modalEditorCont.appendChild(inputRole);
                    let button = document.createElement('input');
                    button.type = 'submit';
                    button.role = 'button';
                    button.name = 'submit';
                    button.setAttribute("onclick", "safeUser()")
                    button.value = 'Сохранить';
                    modalEditorCont.appendChild(button);

                    modalEditor.style.display = 'flex';
                }
            }

        }

        function safeUser() {

            // chbox = document.getElementById('id');
            var email = document.getElementById('input_email');
            var name = document.getElementById('input_name');
            var password = document.getElementById('input_password');
            var role = document.getElementById('input_role');
            var hidden = document.getElementById('hid');
            // var checkboxes = document.querySelectorAll('input[type="checkbox"]')
            document.cookie = "email=" + email.value;
            document.cookie = "name=" + name.value;
            document.cookie = "role=" + role.value; 
            document.cookie = "password=" + password.value; 
            
            // if (checkbox.checked) {
            fetch('/admin/user' + hidden.value, {
                method: "PUT",
            });
           
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