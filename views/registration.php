<?php
require_once("../src/db_files/DB_config.php");
require_once("../src/services/Validation.php");


if(isset($_POST['reg_button'])) {

     //get data from db and check for repeat
     try{

             $validation = new Validation($_POST['login'], $_POST['password'], $_POST['password_verify'], $_POST['email']);

             $connection = new DB_config("root", "");

             $get_login = $connection->getDBConnection()->prepare("SELECT login, email FROM users WHERE login = :login");

             $get_login->bindValue(":login", Validation::inputValidate($_POST['login']), PDO::PARAM_STR);

             $get_login->execute();

             $loginAndEmailFromDbByInsertedCondition = $get_login->fetch(PDO::FETCH_ASSOC);

             $validation->EmptyCheck();

             print_r($validation);

             $connection = null;


     } catch (PDOException $e) {
         die($e->getMessage());
     }

/*


         //check for correct symbols (only letters and numbers)

     (!preg_match("/[a-zA-ZА-ЯЁа-яё0-9]/u", inputValidate($_POST['password']))){
         $passwordErrors = $errors['password_errors']['incorrect_type_of_chars'];
     };

     //check password for correlation "email" and "email password"
     if (inputValidate($_POST['password_verify']) !== inputValidate($_POST['password'])){
         $repeatPasswordErrors = $errors['password_verify_errors']['don`t_found'];

         //check for non empty
     } elseif (inputValidate($_POST['password_verify']) == ""){
         $repeatPasswordErrors = $errors['password_verify_errors']['empty'];
     };


         //check for correct symbols in email input
     } elseif (!filter_var(inputValidate($_POST['email']), FILTER_VALIDATE_EMAIL)) {
         $emailErrors = $errors['email_errors']['don`t_contain_symbol'];
     }




    if (empty($loginErrors) && empty($repeatPasswordErrors) && empty($emailErrors)) {

        $login = inputValidate($_POST['password']);
        $password = inputValidate($_POST['password']);
        $email = inputValidate($_POST['email']);

        try{

        $connection = new DB_config("root", "");

        $insert_data = $connection->getDBConnection()->prepare("INSERT INTO users(login,password,email) VALUES (:login, :password, :email)");

        $insert_data->bindParam(":login", $login, PDO::PARAM_STR);
        $insert_data->bindParam(":password", $password, PDO::PARAM_STR);
        $insert_data->bindParam(":email", $email, PDO::PARAM_STR);

        $result = $insert_data->execute();

        $connection = null;

        if($result) {
            header('Location: index.php');
        }

    } catch (PDOException $e) {
            die($e->getMessage());
        }

*/
 }


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Сторінка реєстрації</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../web-inf/stylesheet/registration.css" rel="stylesheet">

    <link href="../web-inf/images/favicon.ico" rel="shortcut icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>

<header class="container-fluid">
    <nav>
        <a href="index.php">
            <img src="../web-inf/images/policeLogo.jpg" alt="logo"/>
        </a>
    </nav>
</header>


<main class="container">

<h3 class="text-center">Форма реєстрації</h3>

<div class="row login_menu">

    <form method="POST" action="<?php $_SERVER['PHP_SELF']?>" class="mx-auto">
        <div class="form-group">
            <label for="login">Логін</label>
            <input type="text" class="form-control" id="login" placeholder="Введіть логін" name="login" minlength="3" maxlength="30" value="<?php if(isset($_POST['login'])) echo $_POST['login']; ?>" ><div class="text-warning input_warnings"><?php if(isset($validation) && $validation->HasErrors("login_errors")) echo $validation->getErrors()["login_errors"];?></div>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" id="password" placeholder="Пароль" name="password" maxlength="30" minlength="3" ><div class="text-warning input_warnings"><?php if(isset($validation) && $validation->HasErrors("password_errors")) echo $validation->getErrors()["password_errors"];?></div>
        </div>
        <div class="form-group">
            <label for="password_verify">Пароль ще раз</label>
            <input type="password" class="form-control" id="password_verify" placeholder="Введіть пароль ще раз" name="password_verify" maxlength="30" ><div class="text-warning input_warnings"><?php if(isset($validation) && $validation->HasErrors("password_verify_errors")) echo $validation->getErrors()["password_verify_errors"];?></div>
        </div>
            <div class="form-group">
            <label for="email">Емейл (Якщо забудете пароль, ми надішлемо його на цю адресу)</label>
            <input type="email" class="form-control" id="email" placeholder="Введіть емейл" name="email" maxlength="30" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>" ><div class="text-warning input_warnings"><?php if(isset($validation) && $validation->HasErrors("email_errors")) echo $validation->getErrors()["email_errors"];?></div>
            </div>
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