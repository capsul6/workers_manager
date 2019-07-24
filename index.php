<?php
require 'db_files/connection.php';

?>
<!doctype html>
<html lang="en">
<head>
    <title>Сторінка входу</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="stylesheet/index.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>


<header class="head">
    <nav>

        <ul>
            <li><a href="index.php"><img src="images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
        </ul>

    </nav>
</header>


<main class="container main">

<h3 class="text-center">Система обліку працівників відділу забезпечення діяльності керівництва ДДЗ НПУ</h3>

<div class="row login_menu">

    <form method="post" class="mx-auto">
        <div class="form-group">
            <label for="exampleInputEmail1">Логін</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введіть логін">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Пароль</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Запам'ятати мене</label>
        </div>
        <button id="login_button" type="submit" class="btn btn-outline-danger" name="login" data-toggle="tooltip" data-placement="right" title="Увійдіть, якщо зареєстровані">Увійти</button>

        <a href="registration.php"><button id="registration_button" type="button" class="btn btn-outline-primary" name="registration" data-toggle="tooltip" data-placement="right" title="Зареєструйтеся, якщо ще цього не зробили">Зареєструватися</button></a>
    </form>

</div>


</main>

<script>
    window.onload = function () {

       let a = $(window).height();
       $("body").css("height", a);

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

    };
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>

