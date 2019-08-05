
<?php
require 'db_files/connection.php';

if(isset($_COOKIE['login'])) {
    header('Location: admin.php');
}

function inputValidate($text) {
    $text = trim($text);
    $text = substr($text,0,29);
    $text = stripslashes($text);
    return $text;
}

$loginErrors = $passwordErrors = "";


$errors = array("login_errors" =>
    array("empty" => "логін не може бути пустим",
    "more_than_thirty_symbols" => "логін не може бути довше 30 символів",
    "less_than_three_symbols" => "логін не може бути коротшим за 3 символи",
    "already_exist" => "користувача з таким логіном не існує"),

    "password_errors" =>
        array("empty" => "пароль не може бути пустим",
        "less_than_three_symbols" => "пароль не може бути коротшим за 3 символи",
        "more_than_thirty_symbols" => "пароль не може бути довше 30 символів",
        "password doesn't match login" => "неправильний пароль для цього логіну")
);


//check login input

if(isset($_POST['login_button'])) {

//check login for non-empty
    if (inputValidate($_POST['login']) == "") {
        $loginErrors = $errors['login_errors']['empty'];

        //check for length no more than 30 symbols
    } elseif (mb_strlen(inputValidate($_POST['login']), "UTF-8") > 30) {
        $loginErrors = $errors['login_errors']['more_than_thirty_symbols'];

        //check for length (less than 3 symbols)
    } elseif (mb_strlen(inputValidate($_POST['login']), "UTF-8") < 3) {
        $loginErrors = $errors['login_errors']['less_than_three_symbols'];
    }

    //get data from db and check is login already in db
    $connection = new mysqli($host, $user, $password, $database);
    if ($connection->error) die($connection->error);
    $query = "SELECT login FROM users WHERE login = " . "'" . inputValidate($_POST['login']) . "';";
    $loginFromDB = $connection->query($query)->fetch_assoc();
    $connection->close();
    if ($loginFromDB['login'] != inputValidate($_POST['login'])) {
        $loginErrors = $errors['login_errors']['already_exist'];
    }


    //check password input
    if (inputValidate($_POST['password']) == "") {
        $passwordErrors = $errors['password_errors']['empty'];

        //check for length no more than 30 symbols
    } elseif (mb_strlen(inputValidate($_POST['password']), "UTF-8") > 30) {
        $passwordErrors = $errors['password_errors']['more_than_thirty_symbols'];

        //check for length (less than 3 symbols)
    } elseif (mb_strlen(inputValidate($_POST['password']), "UTF-8") < 3) {
        $passwordErrors = $errors['password_errors']['less_than_three_symbols'];
    }

    //get data from db and check is login with this password already in db
    $connection = new mysqli($host, $user, $password, $database);
    $connection->set_charset("utf8");
    if ($connection->error) die($connection->error);
    $query = "SELECT login, password FROM users WHERE login = " . "'" . inputValidate($_POST['login']) . "';";

    $loginAndPasswordFromDB = $connection->query($query);
    $result = $loginAndPasswordFromDB->fetch_assoc();
    $connection->close();

    //check if all is ok
    if($result['login'] == inputValidate($_POST['login']) && password_verify($_POST['password'], $result['password']) && empty($loginErrors)&& empty($passwordErrors)) {

        //if all is ok set session
        session_start();
        $_SESSION['login'] = $result['login'];
        $_SESSION['name'] = $result['name'];
        $_SESSION['surname'] = $result['surname'];
        //and send user to next page
        header("Location: admin.php");

        //if isset remember_me check_button, set cookies
        if(isset($_POST['remember_me'])) {
            setrawcookie('login', $result['login'], time() + (7 * 24 * 60 * 60));
            setrawcookie('name', $result['name'], time() + (7 * 24 * 60 * 60));
            setrawcookie('surname', $result['surname'], time() + (7 * 24 * 60 * 60));
        }


    //some check
    } elseif (empty($loginErrors) && inputValidate($_POST['password']) == "") {
        $passwordErrors = $errors['password_errors']['empty'];
    }
    // check if password dont matches login
    elseif(!password_verify(inputValidate($_POST['password']), $result['password'])) {
           $passwordErrors = $errors['password_errors']['password doesn\'t match login'];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Сторінка входу</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="stylesheet/index.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>


<header class="head">
    <nav>

        <ul class="d-flex align-items-center">
            <li><a href="index.php"><img src="images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
        </ul>

    </nav>
</header>


<main class="container main">

<h3 class="text-center">Система обліку працівників відділу забезпечення діяльності керівництва ДДЗ НПУ</h3>

<div class="row login_menu">

    <form method="post" class="mx-auto">
        <div class="form-group">
            <label for="exampleInputLogin">Логін</label>
            <input type="text" name="login" class="form-control" id="exampleInputLogin" autofocus="autofocus" placeholder="Введіть логін" minlength="3" maxlength="30" value="<?php if(isset($_POST['login_button'])){echo $_POST['login'];}?>"><div class="text-warning input_warnings"><?php echo $loginErrors;?></div>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Пароль</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль" minlength="3" maxlength="30" name="password" value="<?php if(isset($_POST['login_button'])){echo $_POST['password'];}?>"><div class="text-warning input_warnings"><?php echo $passwordErrors;?></div>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember_me">
            <label class="form-check-label" for="exampleCheck1">Запам'ятати мене</label>
        </div>

        <button id="login_button" type="submit" class="btn btn-outline-danger" name="login_button" data-toggle="tooltip" data-placement="right" title="Увійдіть, якщо зареєстровані">Увійти</button>

        <a href="registration.php"><button id="login_button" type="button" class="btn btn-outline-primary" name="login_button" data-toggle="tooltip" data-placement="right" title="Зареєструйтеся, якщо ще цього не зробили">Зареєструватися</button></a>
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

