<?php
 require("db_files/connection.php");

//define connection
$connection = new mysqli($host, $user, $password, $database);

 function inputValidate(string $text) {
     $text = trim($text);
     $text = substr($text,0,29);
     return $text;
 }

 $loginErrors = $passwordErrors = $emailErrors = $repeatPasswordErrors = "";


 $errors = array("login_errors" => array("empty" => "логін не може бути пустим",
                                         "more_than_thirty_symbols" => "логін не може бути довше 30 символів",
                                         "less_than_three_symbols" => "логін не може бути коротшим за 3 символи",
                                         "incorrect_type_of_chars" => "логін повинен складатися з букв та/або цифр",
                                         "already_exist" => "користувач з таким логіном вже існує"),

                 "password_errors" => array("empty" => "пароль не може бути пустим",
                                            "less_than_three_symbols" => "пароль не може бути коротшим за 3 символи",
                                            "more_than_thirty_symbols" => "пароль не може бути довше 30 символів",
                                            "incorrect_type_of_chars" => "пароль повинен складатися з букв та/або цифр"),


                 "password_verify_errors" => array("empty" => "повторний пароль не може бути пустим",
                                                   "don`t_found" => "повторний пароль не співпадає з основним"),

                 "email_errors" => array("empty" => "емейл не може бути пустим",
                                         "don`t_contain_symbol" => "це не схоже на емейл адресу",
                                         "already_exist" => "користувач з таким емейлом вже існує")
 );

 if(isset($_POST['reg_button'])) {

     //get data from db and check for repeat

     if(!empty($_POST['login'])) {
         $query = "SELECT login FROM users WHERE login = " . "'" . inputValidate($_POST['login']) . "';";
         $loginFromForm = $connection->query($query);
         if (!$loginFromForm) die ($connection->error);

         if ($loginFromForm->num_rows > 0) {
             while ($row = $loginFromForm->fetch_assoc()) {
                 if (inputValidate($_POST['login']) == $row['login']) {
                     $loginErrors = $errors['login_errors']['already_exist'];
                 }
             }
         }
     }

     //verify login input

     //check for non empty

     if (inputValidate($_POST['login'] == '')) {

         $loginErrors = $errors['login_errors']['empty'];

         //check for length
     } elseif (inputValidate(mb_strlen($_POST['login'], "UTF-8")) > 30) {

         $loginErrors = $errors['login_errors']['more_than_thirty_symbols'];

         //check for length (less than 3 symbols)
     } elseif (inputValidate(mb_strlen($_POST['login'], "UTF-8")) < 3) {

         $loginErrors = $errors['login_errors']['less_than_three_symbols'];

         //check for correct symbols (only letters and numbers)
     } elseif (!preg_match("/[a-zA-ZА-ЯЁа-яё0-9]/u", inputValidate($_POST['login']))) {

         $loginErrors = $errors['login_errors']['incorrect_type_of_chars'];

     };


     ///verify password input

     /// check for non empty
     if (inputValidate($_POST['password'] == '')){

         $passwordErrors = $errors['password_errors']['empty'];

         //check for length
     } elseif
     (inputValidate(mb_strlen($_POST['password'], "UTF-8")) > 30){

         $passwordErrors = $errors['password_errors']['more_than_thirty_symbols'];

         //check for length (less than 3 symbols)
     } elseif
     (inputValidate(mb_strlen($_POST['password'], "UTF-8")) < 3){

         $passwordErrors = $errors['password_errors']['less_than_three_symbols'];

         //check for correct symbols (only letters and numbers)
     } elseif
     (!preg_match("/[a-zA-ZА-ЯЁа-яё0-9]/u", inputValidate($_POST['password']))){

         $passwordErrors = $errors['password_errors']['incorrect_type_of_chars'];
     };



     //check password verify for conformity "email" and "email password"
     if (inputValidate($_POST['password_verify']) !== inputValidate($_POST['password'])){
         $repeatPasswordErrors = $errors['password_verify_errors']['don`t_found'];

         //check for non empty
     } elseif (inputValidate($_POST['password_verify']) == ""){
         $repeatPasswordErrors = $errors['password_verify_errors']['empty'];
     };


      //check email for non empty
     if (inputValidate($_POST['email']) == "") {
         $emailErrors = $errors['email_errors']['empty'];
     }

     //check for correct symbols in email input
     if(!filter_var(inputValidate($_POST['email']), FILTER_VALIDATE_EMAIL)) {
         $emailErrors = $errors['email_errors']['don`t_contain_symbol'];
     }

     $email12 = inputValidate($_POST['email']);

     //get data from db for "already exist" error check
     if(!empty($_POST['email'])) {
         $query = "SELECT email FROM users WHERE email = " . "'" . $email12 . "';";
         $emailFromForm = $connection->query($query);
         if (!$emailFromForm) die ($connection->error);

         if ($emailFromForm->num_rows > 0) {
             while ($row = $emailFromForm->fetch_assoc()) {
                 if (inputValidate($_POST['email']) == $row['email']) {
                     $emailErrors = $errors['email_errors']['already_exist'];
                 }
             }
         }
     }


    if (empty($loginErrors) && empty($loginErrors)&& empty($repeatPasswordErrors)&& empty($emailErrors)) {

        $query = "INSERT INTO users(login,password,email) VALUES (" . "'" . inputValidate($_POST['login']) . "'," . "'" . inputValidate($_POST['password']) . "',"  . "'" . inputValidate($_POST['email']) . "');" ;

        if($connection->query($query) == true) {
            $connection->close();
            header('Location: http://localhost/ddz_info/index.php');
        }

    }
 }

 ?>
<!doctype html>
<html lang="en">
<head>
    <title>Сторінка реєстрації</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="stylesheet/registration.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>


<header class="head">
<nav>

    <ul class="d-flex justify-content-between align-items-center">
        <li><a href="index.php"><img src="images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
        <?php if(!isset($_SESSION['login'])) {
            echo '<li><span class="text-info">Ви не авторизовані </span><a href="index.php"><button type="button" class="btn btn-info">Авторизуватися</button></a></li>';
    } else {
            echo '<li><span class="text-info">Доброго дня ' . $_SESSION['login'] . ' </span><a href="logout.php"><button type="submit" class="btn btn-info">Вийти</button></a></li>';
        }
        ?>
    </ul>

</nav>
</header>


<main class="container main">

<h3 class="text-center">Форма реєстрації</h3>

<div class="row login_menu">

    <form method="post" action="registration.php" class="mx-auto">
        <div class="form-group">
            <label for="Login">Логін</label>
            <input type="text" class="form-control" id="Login" placeholder="Введіть логін" name="login" minlength="3" maxlength="30" value="<?php if(isset($_POST['login'])){echo $_POST['login'];}?>"><span class="text-warning"><?php echo $loginErrors;?></span>
        </div>
        <div class="form-group">
            <label for="Password">Пароль</label>
            <input type="password" class="form-control" id="Password" placeholder="Пароль" name="password" maxlength="30" minlength="3"><span class="text-warning"><?php echo $passwordErrors;?></span>
        </div>
        <div class="form-group">
            <label for="Password_verify">Пароль ще раз</label>
            <input type="password" class="form-control" id="password_verify" placeholder="Введіть пароль ще раз" name="password_verify" maxlength="30"><span class="text-warning"><?php echo $repeatPasswordErrors;?></span>
        <div class="form-group">
            <label for="Email">Емейл (Якщо забудете пароль, ми надішлемо його на цю адресу)</label>
            <input type="email" class="form-control" id="email" placeholder="Введіть емейл" name="email" maxlength="30" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"><span class="text-warning"><?php echo $emailErrors;?></span>



        <button id="registration_button" type="submit" class="btn btn-outline-success" name="reg_button" data-toggle="tooltip" data-placement="right" title="Натисніть, щоб зареєструватися">Зареєструватися</button>

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