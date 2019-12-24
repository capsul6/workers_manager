<?php
require_once('../db_files/DBconfig.php');

session_start();
//check for available session or cookies
if(empty($_SESSION['login']) && empty($_COOKIE['login'])) {
    header("Location: index.php");
}

//connect to db and get information based on Session "login" value for user account
try {
    $query = DBconfig::getDBConnection()->prepare("SELECT e.name, e.surname, e.image, e.position, e.image_file_name
    FROM workers e
    LEFT JOIN users a
    ON e.user_id = a.id
    WHERE a.login = :user_login");
    $query->bindValue(':user_login', $_SESSION['login'], PDO::PARAM_STR);
    $query->execute();
    $sessionUser = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
       echo "Error with content: " . $e->getMessage();
    }

//select all users those presented in DB for user_list
try {
    $query = DBconfig::getDBConnection()->query("SELECT name, surname, position, user_id FROM workers");
    $AllUsers = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error with content: " . $e->getMessage();
}


//if checkbox was clicked than start searching info in DB for selected user
if(isset($_GET['user_id'])) {

    try {
        $queryForUserInfo = DBconfig::getDBConnection()->prepare("SELECT position, dateOfBirth, rank, tellNumber, worker_id
                                               FROM workers
                                               WHERE user_id = :id");
        $queryForUserInfo->bindValue(":id", $_GET['user_id'], PDO::PARAM_INT);
        $queryForUserInfo->execute();
        $queryForUserInfoResult = $queryForUserInfo->fetch(PDO::FETCH_ASSOC);

        $sth1 = DBconfig::getDBConnection()->prepare("SELECT e.date_come, e.date_return, e.outside_type
                                      FROM outside_records e
                                      LEFT JOIN workers w on e.worker_id = w.worker_id
                                      WHERE w.user_id = :id
                                      ORDER BY date_return DESC");
        $sth1->bindValue(":id", $_GET['user_id'], PDO::PARAM_INT);
        $sth1->execute();
        $outsideSchedule = $sth1->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e){
        echo $e->getMessage();
    };
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Статус працівників</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../stylesheet/admin.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>

<header class="container-fluid">
    <nav>
        <ul>
            <div class="row">
            <!--logo-->
            <li class="col-xl-3 col-lg-3 nav_left"><a href="index.php"><img src="../images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
            <!--navigation -->
            <li class="col-xl-6 col-lg-6 d-flex justify-content-center align-items-center nav_center">
                <a href="information_page.php">Головна</a>
                <a
                    <?php
                    if(isset($_SESSION['login']) && $_SESSION['login'] == "capsul6" || isset($_COOKIE['login']) && $_COOKIE['login'] == "capsul6") {
                        echo "href='admin_page.php'";
                    }
                    else {
                        echo "aria-disabled=\"true\"";
                    }
                    ?> >Сторінка адміністратора</a>
                <a href="edit_profile_page.php">Редагування та внесення данних</a>
            </li>

            <!--Photo and information-->
            <li class="col-xl-3  col-lg-3  nav_right">
                <div class="card">
                    <div class="card-body d-flex flex-row justify-content-between align-items-center">
                    <img class="card-img-top" src="../images/<?php echo $sessionUser['image_file_name'];?>" alt="Відсутнє зображення">
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
                        <a href="edit_profile_page.php" class="btn btn-primary btn-sm">Редагувати профіль</a>
                        <a href="logout.php" class="btn btn-dark btn-sm">Вийти</a>
                 </div>
             </li>
        </ul>
    </nav>
</header>

<div class="container main_block">

            <div class="row">

                <div class="col-sm-5 col-md-5 col-lg-5 left_panel">
                    <div>
                    <h3 class="text-center">Список працівників</h3>
                    </div>

                    <table class="table table-hover">

                        <thead class="thead-dark">
                        <tr>

                        <th></th>
                        <th>Ім'я</th>
                        <th>Фамілія</th>
                        <th>Посада</th>

                        </tr>
                        </thead>

                        <tbody>

                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="get" id="listOfUsers_form">

                        <?php foreach ($AllUsers as $user):?>

                        <tr class='usersList'>
                        <td><input type='checkbox' name='user_id' value='<?php if(isset($user)){echo $user['user_id'];}?>'></td>
                        <td><?= $user['name'];?></td>
                        <td><?= $user['surname'];?></td>
                        <td><?= $user['position'];?></td>
                        </tr>

                        <?php endforeach; ?>

                        </form>

                        </tbody>

                        </table>

    </div>

    <div class="col-sm-7 col-md-7 col-lg-7 right_panel">
        <h3 class="text-center">Інформація про працівника</h3>
        <table class="table table-hover">

            <thead class="thead-dark">
            <tr>
                <th >Посада</th>
                <th >Дата народження</th>
                <th >Звання</th>
                <th >Телефон</th>
            </tr>
            </thead>

            <tbody>

            <?php if(isset($queryForUserInfoResult)): ?>
            <tr>
                <td id=\"resultPosition\"><?= $queryForUserInfoResult['position']; ?></td>
                <td id=\"resultDateOfBirth\"><?= $queryForUserInfoResult['dateOfBirth']; ?></td>
                <td id=\"resultRank\"><?= $queryForUserInfoResult['rank']; ?></td>
                <td id=\"resultTellNumber\"><?= $queryForUserInfoResult['tellNumber']; ?></td>
            </tr>
            <?php else: ?>
            <tr>
                <td id=\"resultPosition\"></td>
                <td id=\"resultDateOfBirth\"></td>
                <td id=\"resultRank\"></td>
                <td id=\"resultTellNumber\"></td>
            </tr>
            <?php endif ;?>

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
                    <?php if(!empty($outsideSchedule)) {
                        if($outsideSchedule[0]['date_return'] >= date('Y-m-d')) {
                            echo "<td width=\"100%\" id=\"colorful_row\">Відсутній на робочому місті</td>";
                        } else {
                            echo "<td width=\"100%\" id=\"colorful_row\">На робочому місці</td>";
                        }
                    } else {
                        echo "<td></td>";
                    }?>
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
                    <?php if(isset($outsideSchedule)):?>
                    <?php foreach ($outsideSchedule as $dates): ?>
                    <ul>
                        <li><?= $dates['outside_type'];?> з <span id=\"resultFrom\"><?= $dates['date_come'];?></span> по <span id=\"resultTo\"><?= $dates['date_return'];?></span></li>
                    </ul>
                    <?php endforeach;?>
                    <?php else: echo ""; ?>
                    <?php endif;?>
                </td>

            </tr>
            </tbody>
            </table>

    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script>

$(document).ready(function(){

        let a = new URL(window.location.href);

        if (a.searchParams.get("user_id") == null) {
            localStorage.clear();
        }

        if (localStorage.getItem("input-value")) {
        for (let i = 0; i < $("input:checkbox").length; i++) {
            if ($("input:checkbox")[i].value == localStorage.getItem("input-value")) {
                $("input:checkbox")[i].setAttribute("checked", "checked");
            }
        }
        }

    let row = $('#colorful_row').html();

    if(row == "Відсутній на робочому місті") {
        $("#status").css("background-color", "red");
    } else if (row == "На робочому місці") {
        $("#status").css("background-color", "lightgreen");
    } else  {
        $("#status").css("background-color", "white");
    }

    $(".usersList input:checkbox").on("change", function(){
    for(let i = 0; i < $("input:checkbox").length; i++) {
        $("input:checkbox")[i].removeAttribute("checked");
    }

    localStorage.setItem("input-value", $(this).val());
    $("#listOfUsers_form").submit();
});
});

</script>

</body>
</html>

