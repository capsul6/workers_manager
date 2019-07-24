
<?php
session_start();
$_SESSION['name'] = 'Олег Олегович';
$name = $_SESSION['name'];
?>
<!doctype html>
<html lang="en">
<head>
    <title>Адміністративна панель</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="stylesheet/admin.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>

<header>
    <nav>

        <ul class="d-flex justify-content-between align-items-center">
            <li><a href="index.php"><img src="images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
            <li> <span class="text-info">Доброго дня <?php echo $name?></span> <a href="index.php"><button type="button" class="btn btn-info">Вийти</button></a></li>
        </ul>

    </nav>
</header>

<div class="container main_block">

<div class="row">

    <div class="col-sm-4 col-md-4 col-lg-4 left_panel">
        <table class="table table-hover">

            <thead>
            <tr>
            <th>№</th>
            <th>Ім'я</th>
            <th>Фамілія</th>
            <th>Посада</th>
            </tr>
            </thead>

            <tbody>
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
        <table class="table table-hover">

            <thead>
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


<script>
    window.onload = function () {
        let b = $(".head").css("height");
        $(".main_block").css("top", b);

        let a = $(window).height();
        $("body").css("height", a);
    };
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</body>
</html>

