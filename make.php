<!DOCTYPE html>
<html>

<head>
    <title>Добро пожаловать в облачное хранилище учебного заведения</title>
</head>

<body>
    <h1>Если вы хотите приступить к работе с хранилище тогда вам нужно будет пройти авторизацию</h1>
    <form action="/login" method="GET">
        <label>
            <h3>Введите логин/email</h3>
        </label>
        <input type="text" name="email" id="email">
        <label>
            <h3>Введите пароль</h3>
        </label>
        <input type="password" name="password" id="password"><br>
        <input type="submit" name="/login" id="submit" value="Войти">
    </form>
</body>

</html>