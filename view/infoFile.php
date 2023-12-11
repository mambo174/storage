<!DOCTYPE html>
<?php
include_once './Controllers/files.php';
?>

<html>

<head>
    <link href="../style/bootstrap.min.css" rel="stylesheet">

</head>


<body>

    <?php

    $id = $_GET['id'];
    $id = preg_replace("/[^0-9]/", '', $id);

    $fileInfo = FilesStorage::showDataFile($id);
    $fileSize = intdiv($fileInfo['size'], 1024);



    echo "
    <div> Имя: " . $fileInfo['name_files'] . "</div>
    <div> Расширение: " . $fileInfo['extension'] . " </div>
    <div> Размер: " . $fileSize . " Кб </div>
    <div> Дата: " . $fileInfo['date_created'] . "  </div>
    <div> Владелец: </div>" ?>
</body>

</html>