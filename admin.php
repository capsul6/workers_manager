<?php
require ('db_files/connection.php');

session_start();
//check for available session or cookies
if(!isset($_SESSION['login']) && !isset($_COOKIE['login'])) {
    header("Location: index.php");
}
//connect db and get
try {
    $PDO_connection = new PDO($dsn, $user, $password, $opt);
    $query = $PDO_connection->prepare("SELECT * FROM workers 
    LEFT JOIN users
    ON user_id = users.id
    WHERE users.login = :user_login");
    $query->bindValue('user_login', $_SESSION['login'], PDO::PARAM_STR);
    $query->execute();
    $sessionUser = $query->fetch(PDO::FETCH_ASSOC);
    $PDO_connection = null;

        } catch (PDOException $e) {
            echo "Error with content: " . $e->getMessage();
        }

try {
    $PDO_connection = new PDO($dsn, $user, $password, $opt);
    $query = $PDO_connection->query("SELECT name, surname, position, user_id FROM workers");
    $AllUsers = $query->fetchAll(PDO::FETCH_ASSOC);
    $PDO_connection = null;

} catch (PDOException $e) {
    echo "Error with content: " . $e->getMessage();
}

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
            <li class="col-xl-3 col-lg-3 nav_left"><a href="index.php"><img src="images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
            <!--navigation -->
            <li class="col-xl-6 col-lg-6 d-flex justify-content-center align-items-center nav_center ">
                <a href="index.php">Головна</a>
                <a
                    <?php
                    if(isset($_SESSION['login']) && $_SESSION['login'] == "capsul6"){
                        echo "href='admin.php'";
                    } elseif(isset($_COOKIE['login']) && $_COOKIE['login'] == "capsul6") {
                        echo "href='admin.php'";
                    }
                    else {
                        echo "";
                    }
                    ?> >Сторінка адміністратора</a>
                <a href="edit_profile.php">Редагування та внесення данних</a>
            </li>

            <!--Photo and information-->
            <li class="col-xl-3  col-lg-3  nav_right">
                <div class="card">
                    <div class="card-body d-flex flex-row justify-content-between align-items-center">
                    <img class="card-img-top" src="images/DSC_0029.jpg" alt="Card image">
                     <div class="text_inside_card">
                         <p class="card-text"><?php if(isset($sessionUser['surname']) && isset($sessionUser['name'])):?>
                                                    <?php echo $sessionUser['surname'] . " " . $sessionUser['name'];?>
                                                    <?php else: echo "Не вказані дані";?>
                                                    <?php endif;?></p>
                         <p class="card-text"><?php if(isset($sessionUser['position'])):?>
                                                 <?php echo  $sessionUser['position'];?>
                                                 <?php else: echo "Не вказані дані";?>
                                                 <?php endif;?>
                         </p>
                     </div>
                    </div>
                        <!--Buttons with logout and edit profile actions-->
                        <a href="edit_profile.php" class="btn btn-primary btn-sm">Редагувати профіль</a>
                        <a href="logout.php" class="btn btn-dark btn-sm">Вийти</a>
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

                        <th>Ім'я</th>
                        <th>Фамілія</th>
                        <th>Посада</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($AllUsers as $user):
                        echo "
                        <!--just for getting id to make db query-->
                        <tr class='user'>
                        <td><input name='name' id='userName' value='{$user['user_id']}'>{$user['name']}</td>
                        <td><input>{$user['surname']}</td>
                        <td><input>{$user['position']}</td>
                        </tr>";
                        endforeach;?>
                        </tbody>

        </table>

    </div>

    <div class="col-sm-8 col-md-8 col-lg-8 right_panel">
        <h3 class="text-center">Інформація про працівника</h3>
        <table class="table table-hover">

            <thead class="thead-dark">
            <tr>
                <th width="35%">Посада</th>
                <th width="20%">Дата народження</th>
                <th width="25%">Звання</th>
                <th width="20%">Телефон</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td id="resultPosition"></td>
                <td id="resultDateOfBirth"></td>
                <td id="resultRank"></td>
                <td id="resultTellNumber"></td>
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
            <tr id="status">
                <td width="100%"></td>
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
                        <li>Відпустка з <span id="resultFrom">____.__.__</span> по <span id="resultTo">____.__.__</span></li>
                    </ul>
                </td>
            </tr>
            </tbody>
            </table>

    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
    $(".user").click(function (event) {
        let a = event.currentTarget.cells[0].firstElementChild.attributes[2].value;
        $.ajax({
            url: "forDetaileWorkerQuery.php",
            type: "POST",
            data: "name=" + a,
            success: function (data) {
                let obj = JSON.parse(data);
                $("#resultPosition").html(obj[0].position);
                $("#resultDateOfBirth").html(obj[0].dateOfBirth);
                $("#resultRank").html(obj[0].rank);
                $("#resultTellNumber").html(obj[0].tellNumber);
                $("#resultFrom").html(obj[0].date_come);
                $("#resultTo").html(obj[0].date_return);


                let todayDate = new Date();
                let fromDateHtml = $("#resultFrom")[0].firstChild.data;
                let toDateHtml = $("#resultTo")[0].firstChild.data;

                let fromDate = new Date(fromDateHtml);
                let toDate = new Date(toDateHtml);

                if(todayDate < fromDate || todayDate > toDate) {
                    $("#status")[0].firstElementChild.innerHTML = "На робочому місці";
                    $("#status")[0].className = "table-success";
                }
                 else{
                    $("#status")[0].firstElementChild.innerHTML = "Відсутній на робочому місці";
                    $("#status")[0].className = "table-danger";
                }



            }
    })
    });

    $("body").click(function () {
            $("#resultPosition").html("");
            $("#resultDateOfBirth").html("");
            $("#resultRank").html("");
            $("#resultTellNumber").html("");
            $("#resultFrom").html("____-__-__");
            $("#resultTo").html("____-__-__");
            $("#status")[0].firstElementChild.innerHTML = "";
            $("#status")[0].className = "";
            }
        );
</script>
</body>
</html>

