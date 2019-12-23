<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Сторінка входу</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../stylesheet/forgot_email.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">
</head>

<body>

<header>

    <nav>
        <ul>
            <li><a href="index.php"><img src="../images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
        </ul>
    </nav>

</header>

<h3 class="text-center">Відновлення паролю</h3>

<div class="row login_menu">

    <form method="post" class="mx-auto">

        <div class="form-group">
            <label for="exampleInputLogin">Логін</label>
            <input type="text" name="login" class="form-control" id="exampleInputLogin" autofocus="autofocus" placeholder="Логін" minlength="3" maxlength="30"><div class="text-warning input_warnings"></div>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail">Емейл адреса</label>
            <input type="email" class="form-control" id="exampleInputEmail" placeholder="Емейл адреса" minlength="3" maxlength="30" name="email"><div class="text-warning input_warnings"></div>
        </div>

        <button type="submit" class="btn btn-outline-primary send_password" name="send_password" data-toggle="tooltip" data-placement="right" title="Відправити пароль">Надіслати пароль</button>

    </form>

</div>






</body>

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
</html>
