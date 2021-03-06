<?php
require_once('../src/db_files/DB_config.php');

session_start();

//check for available session or cookies
if(empty($_SESSION['login']) && empty($_COOKIE['login'])) {
    header("Location: index.php");
}


//connect to db and get information based on Session "login" value for user account
try {
    $connection = new DB_config("root", "");
    $query = $connection->getDBConnection()->prepare("SELECT e.name, e.surname, e.image, e.position, e.image_file_name, e.permission_type
    FROM workers e
    LEFT JOIN users a
    ON e.user_id = a.id
    WHERE a.login = :user_login");
    $query->bindValue(':user_login', $_SESSION['login'], PDO::PARAM_STR);
    $query->execute();
    $sessionUser = $query->fetch(PDO::FETCH_ASSOC);
    $connection = null;
    } catch (PDOException $e) {
       echo "Error with content: " . $e->getMessage();
    }


//select all users those are presented in DB for user_list
try {
    $connection = new DB_config("root", "");
    $query = $connection->getDBConnection()->query("SELECT name, surname, position, user_id FROM workers");
    $AllUsers = $query->fetchAll(PDO::FETCH_ASSOC);
    $connection = null;
} catch (PDOException $e) {
    echo "Error with content: " . $e->getMessage();
}


//if the checkbox button was clicked on then we`re starting to search info in DB for selected user
if(isset($_GET['user_id'])) {

    try {
        $connection = new DB_config("root", "");
        $queryForUserInfo = $connection->getDBConnection()->prepare("SELECT position, dateOfBirth, rank, tellNumber, worker_id
                                               FROM workers
                                               WHERE user_id = :id");
        $queryForUserInfo->bindValue(":id", $_GET['user_id'], PDO::PARAM_INT);
        $queryForUserInfo->execute();
        $queryForUserInfoResult = $queryForUserInfo->fetch(PDO::FETCH_ASSOC);

        $sth1 = $connection->getDBConnection()->prepare("SELECT e.date_come, e.date_return, e.outside_type
                                      FROM outside_records e
                                      LEFT JOIN workers w
                                      ON e.worker_id = w.worker_id
                                      WHERE w.user_id = :id
                                      ORDER BY date_return DESC");
        $sth1->bindValue(":id", $_GET['user_id'], PDO::PARAM_INT);
        $sth1->execute();
        $outsideSchedule = $sth1->fetchAll(PDO::FETCH_ASSOC);

        $connection = null;

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

    <link href="../web-inf/stylesheet/admin_page.css" rel="stylesheet">

    <link href="../web-inf/images/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>

<?php include_once "navigation_panel/header.php";?>

<div class="container main_block">

            <div class="row">

                <div class="col-sm-5 col-md-5 col-lg-5 left_panel">
                    <div>
                    <h3 class="text-center">Список працівників</h3>
                    </div>

                    <article>
                    <table class="table table-hover">

                        <thead class="thead-dark">

                        <tr>
                        <th style="width:10%"></th>

                        <th style="width:15%">Ім'я</th>

                        <th style="width:15%">Фамілія</th>

                        <th style="width:60%">Посада</th>

                        </tr>
                        </thead>

                        <tbody>



                        <form action="<?= $_SERVER['PHP_SELF']?>" method="GET" id="listOfUsers_form">

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

                    </article>
                    <p id="numberOfWorkers">Всього працівників: <?php echo count($AllUsers)?></p>

    </div>

    <div class="col-sm-7 col-md-7 col-lg-7 right_panel">

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

            <?php if(isset($queryForUserInfoResult)): ?>
            <tr>
                <td id="resultPosition" style="width: 40%"><?= $queryForUserInfoResult['position']; ?></td>
                <td id="resultDateOfBirth" style="width: 20%"><?= $queryForUserInfoResult['dateOfBirth']; ?></td>
                <td id="resultRank" style="width: 20%"><?= $queryForUserInfoResult['rank']; ?></td>
                <td id="resultTellNumber" style="width: 20%"><?= $queryForUserInfoResult['tellNumber']; ?></td>
            </tr>
            <?php else: ?>
            <tr>
                <td id="resultPosition"></td>
                <td id="resultDateOfBirth"></td>
                <td id="resultRank"></td>
                <td id="resultTellNumber"></td>
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
                    <?php
                    if(!empty($outsideSchedule)) {
                        if($outsideSchedule[0]['date_come'] <= date('Y-m-d') && date('Y-m-d') >= $outsideSchedule[0]['date_return']) {
                            echo "<td width=\"100%\" id=\"colorful_row\">На робочому місці</td>";
                        } else {
                            echo "<td width=\"100%\" id=\"colorful_row\">Відсутній на робочому місті</td>";
                        }
                    } elseif(isset($outsideSchedule)) {
                        echo "<td>Неможливо визначити статус, так як відсутня інформація про відсутність та/або присутність</td>";
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
                    <?php if(!empty($outsideSchedule) && isset($outsideSchedule)):?>
                    <?php foreach ($outsideSchedule as $dates): ?>
                    <ul>
                        <li><?= $dates['outside_type'];?> з <span id="resultFrom"><?= $dates['date_come'];?></span> по <span id="resultTo"><?= $dates['date_return'];?></span></li>
                    </ul>
                    <?php endforeach;?>
                    <?php else: echo "Відсутня історія присутності"; ?>
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

