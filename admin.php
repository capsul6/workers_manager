<?php
require ('db_files/connection.php');
session_start();
if(!isset($_SESSION['login']) && !isset($_COOKIE['login'])) {
    header("Location: index.php");
}

$connection = new mysqli($host, $user, $password, $database);
$connection->set_charset("utf8");
if ($connection->error) die($connection->error);
$query = "SELECT name, surname, position
          FROM workers
          FULL JOIN users
          ON users.id = user_id
          WHERE users.login =" . "'" . $_SESSION['login'] . "'";

$result = $connection->query($query);
$sessionUser = $result->fetch_assoc();
$connection->close();

?>
<!doctype html>
<html lang="en">
<head>
    <title>Адміністративна панель</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="stylesheet/admin.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>

<header class="container-fluid">
    <nav>
        <ul>
            <div class="row">
            <!--logo-->
            <li class="col-xl-6 col-lg-6 nav_left"><a href="index.php"><img src="images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>

            <!--Photo and information-->
            <li class="col-xl-3 offset-xl-3 col-lg-5 offset-lg-1 nav_right">
                <div class="card">
                    <div class="card-body d-flex flex-row justify-content-between align-items-center">
                    <img class="card-img-top" src="images/DSC_0029.jpg" alt="Card image" width="125px" height="125px">
                     <div class="text_inside_card">
                         <p class="card-title"><b><?php echo $sessionUser['surname'] . " " . $sessionUser['name'];?></b></p>
                         <p class="card-text"><b><?php echo $sessionUser['position'];?></b></p>
                     </div>
                    </div>
                        <!--Buttons with logout and edit profile actions-->
                        <a href="#" class="btn btn-primary">Редагувати профіль</a>
                        <a href="logout.php" class="btn btn-dark">Вийти</a>
                 </div>
             </li>
        </ul>
    </nav>
</header>

            <div class="container main_block">

            <div class="row">

                <div class="col-sm-4 col-md-4 col-lg-4 left_panel">
                    <div>
                    <h3 class="text-center">Список працівників</h3>
                    </div>
                    <table class="table table-hover">

                        <thead class="thead-dark">
                        <tr>
                        <th>№</th>
                        <th>Ім'я</th>
                        <th>Фамілія</th>
                        <th>Посада</th>
                        </tr>
                        </thead>

                        <tbody>
                        <!--1-->
            <tr>
                <td>1</td>
                <td>Сергій</td>
                <td>Бойчук</td>
                <td>Помічник заступника Голови</td>
            </tr>
            <!--2-->
            <tr>
                <td>1</td>
                <td>Сергій</td>
                <td>Бойчук</td>
                <td>Помічник заступника Голови</td>
            </tr>
            </tbody>

        </table>
    </div>

    <div class="col-sm-8 col-md-8 col-lg-8 right_panel">
        <h3 class="text-center">Інформація про працівника</h3>
        <table class="table table-hover">

            <thead class="thead-dark">
            <tr>
                <th>Посада</th>
                <th>Дата народження</th>
                <th>Звання</th>
                <th>Телефон</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>Помічник заступника</td>
                <td>11.22.1999</td>
                <td>Підполковник поліції</td>
                <td>0598765418</td>
            </tr>
            </tbody>

            </table>

            <table class="table table-hover">
            <thead>
            <tr>
                <th>Статус присутності</th>
            </tr>
            </thead>

            <tbody>
            <tr class="table-success">
                <td>На робочому місці</td>
            </tr>
            </tbody>

            </table>

            <table class="table table-hover">
            <thead>
            <tr>
                <th>Історія присутності</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table-warning">
                <td>
                    <ul>
                        <li>Відпустка з 20.01.2019 - по 20.02.2019</li>
                    </ul>
                </td>
            </tr>
            </tbody>
            </table>

    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</body>
</html>

