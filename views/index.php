<?php

spl_autoload_register(function ($name){
    require_once "../src/db_files/" . "$name" . ".php";
});

session_start();

if(isset($_COOKIE['login']) or isset($_SESSION['login'])) {
    header('Location: admin_page.php');
}

function inputValidate($text) {
    $text = trim($text);
    $text = stripslashes($text);
    return $text;
}

$loginErrors = $passwordErrors = "";


$errors = array("login_errors" =>
    array("empty" => "логін не може бути пустим",
    "more_than_thirty_symbols" => "логін не може бути довше 30 символів",
    "less_than_three_symbols" => "логін не може бути коротшим за 3 символи",
    "there`s_no_user_with_the_login" => "користувач з таким логіном відсутній у базі даних"),

    "password_errors" =>
        array("empty" => "пароль не може бути пустим",
        "less_than_three_symbols" => "пароль не може бути коротшим за 3 символи",
        "more_than_thirty_symbols" => "пароль не може бути довше 30 символів",
        "password doesn't match login" => "неправильний пароль для цього логіну")
);



if(isset($_POST['login_button'])) {

/*
     * Block of code for checking "login" field that was typed by user
*/
    //get "login" field values from db based on user input
    try {
        //get data from db and check if login is already in db or there`s no user with user`s typed "login"
        $connection = new DB_config("root", "");
        $queryForGetUserLogin = $connection->getDBConnection()->prepare("SELECT login FROM users WHERE login = :login");
        $queryForGetUserLogin->bindValue(":login", inputValidate($_POST['login']), PDO::PARAM_STR);
        $queryForGetUserLogin->execute();
        $resultUserLogin = $queryForGetUserLogin->fetch(PDO::FETCH_ASSOC);
        if ($resultUserLogin['login'] != inputValidate($_POST['login'])) {
            $loginErrors = $errors['login_errors']['there`s_no_user_with_the_login'];
        }
        $connection = null;
    } catch (PDOException $e) {
       die($e->getMessage());
    }

    //check "login" field for non-empty
    if (inputValidate($_POST['login']) == "") {
        $loginErrors = $errors['login_errors']['empty'];

    //check if length of typed "login" field is more than 30 symbols
    } elseif (mb_strlen(inputValidate($_POST['login']), "UTF-8") > 30) {
        $loginErrors = $errors['login_errors']['more_than_thirty_symbols'];

    //check if length of typed "login" field is less than 3 symbols
    } elseif (mb_strlen(inputValidate($_POST['login']), "UTF-8") < 3) {
        $loginErrors = $errors['login_errors']['less_than_three_symbols'];

    //checking for that the typed value in "login" field was not found in db and "password" field is empty
    } elseif ($loginErrors == $errors['login_errors']['there`s_no_user_with_the_login'] && inputValidate($_POST['password'] == "")){
        $passwordErrors = $errors['password_errors']['empty'];
    }


    /*
     * Block of code for checking "password" field that was typed by user
    */
    //getting "login" and "password" values from DB based on user typed login and password
    try {
      $connection = new DB_config("root", "");
      $queryForGetUserLoginAndPassword = $connection->getDBConnection()->prepare("SELECT login, password FROM users WHERE login = :login");
      $queryForGetUserLoginAndPassword->bindValue(":login", inputValidate($_POST['login']), PDO::PARAM_STR);
      $queryForGetUserLoginAndPassword->execute();
      $result = $queryForGetUserLoginAndPassword->fetch(PDO::FETCH_ASSOC);
      $connection = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    //checking that the "password" field that was typed by user is empty
    if (inputValidate($_POST['password']) == "") {
        $passwordErrors = $errors['password_errors']['empty'];

    //checking if length of typed "password" field is more than 30 symbols
    } else if (mb_strlen(inputValidate($_POST['password']), "UTF-8") > 30) {
        $passwordErrors = $errors['password_errors']['more_than_thirty_symbols'];

    //checking if length of typed "password" field is less than 3 symbols
    } else if (mb_strlen(inputValidate($_POST['password']), "UTF-8") < 3) {
        $passwordErrors = $errors['password_errors']['less_than_three_symbols'];

    //checking that the "login" field that was typed by user is not empty and typed password doesn`t match real password
    } else if (!empty($result['login']) && !password_verify(inputValidate($_POST['password']), $result['password'])) {
        $passwordErrors = $errors['password_errors']['password doesn\'t match login'];

    //checking that the "login" field is filled success but "password" field is empty
    } else if (empty($loginErrors) && inputValidate($_POST['password']) == "") {
        $passwordErrors = $errors['password_errors']['empty'];
    }

    //check if all inputs are correctly
    if ($result['login'] == inputValidate($_POST['login']) && password_verify($_POST['password'], $result['password']) && empty($loginErrors) && empty($passwordErrors)) {

    //if all inputs are correctly set session
    session_start();
    $_SESSION['login'] = $result['login'];

    //if isset remember_me check_button, set cookies
    if (isset($_POST['remember_me'])) {
        setrawcookie('login', $result['login'], time() + (7 * 24 * 60 * 60));
     }
    //and send user to next page
     header("Location: admin_page.php");
     }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Сторінка входу</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../web-inf/stylesheet/index.css" rel="stylesheet">
    <link href="../web-inf/images/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>


<header class="container-fluid">
    <nav>
    <a href="index.php">
        <img src="../web-inf/images/Webp.net-resizeimage.jpg" alt="logo"/>
    </a>
    </nav>
</header>


<main class="container">

<h3 class="text-center">Система обліку працівників відділу забезпечення діяльності керівництва ДДЗ НПУ</h3>

<div class="row login_menu">

    <form method="POST" class="mx-auto">
        <div class="form-group">
            <label for="exampleInputLogin">Логін</label>
            <input type="text" name="login" class="form-control" id="exampleInputLogin" autofocus="autofocus" placeholder="Введіть логін" minlength="3" maxlength="30" value="<?php if(isset($_POST['login_button'])){echo $_POST['login'];}?>" required><div class="text-warning input_warnings"><?php echo $loginErrors;?></div>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Пароль</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль" minlength="3" maxlength="30" name="password" value="<?php if(isset($_POST['login_button'])){echo $_POST['password'];}?>" required><div class="text-warning input_warnings"><?php echo $passwordErrors;?></div>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember_me">
            <label class="form-check-label" for="exampleCheck1">Запам'ятати мене</label>
        </div>

        <button type="submit" class="btn btn-outline-danger w-100 login_button" name="login_button" data-toggle="tooltip" data-placement="right" title="Увійдіть, якщо зареєстровані">Увійти</button>

        <a href="registration.php"><button type="button" class="btn btn-outline-primary w-100 login_button" name="login_button" data-toggle="tooltip" data-placement="right" title="Зареєструйтеся, якщо ще цього не зробили">Зареєструватися</button></a>

        <a href="forgot_email.php" id="forgot_password_text"><p class="font-italic text-center text-white" >Забули пароль? Натисність, щоб поновити</p></a>
    </form>

</div>


</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>

    window.onload = function () {
        let a = window.innerHeight;
        $("body").css("height", a);
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    };


</script>


</body>
</html>

