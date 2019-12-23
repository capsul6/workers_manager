<?php
require_once('../db_files/connection.php');

session_start();


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

//get all posts from DB
try {
    $query = DBconfig::getDBConnection()->query("SELECT file_location, description, posted_date, article_id
    FROM articles ORDER BY posted_date;");
    $postsList = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error with content: " . $e->getMessage();
}


if(isset($_POST['update'])) {

        if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

            try {
                $query = DBconfig::getDBConnection()->prepare("UPDATE articles SET
                    file_location = ?,
                    description = ?,
                    posted_date = ?
                    WHERE article_id = ?");

                $result = $query->execute(array(
                    "../files/" . $_FILES['file']['name'],
                    $_POST['description'],
                    $_POST['posted_date'],
                    $_POST['article_id']));

                if($result){
                    header( "Location:" . $_SERVER['PHP_SELF'] . "?updated=true&" . "article_id=" . $_POST['article_id']);
                }


            } catch (PDOException $e) {
                echo $e->getMessage();
            }



        } elseif (isset($_FILES['file']) && $_FILES['file']['size'] <= 0) {

            try {
                $query = DBconfig::getDBConnection()->prepare("UPDATE articles SET
                    description = ?,
                    posted_date = ?
                    WHERE article_id = ?");

                $result = $query->execute(array(
                    $_POST['description'],
                    $_POST['posted_date'],
                    $_POST['article_id']));

                if($result){
                   header( "Location:" . $_SERVER['PHP_SELF'] . "?updated=true&" . "article_id=" . $_POST['article_id']);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

} elseif (isset($_POST['delete'])) {

        try {
            $getFilePath = DBconfig::getDBConnection()->query("SELECT file_location FROM articles WHERE article_id =" . $_POST['article_id'])->fetch();

            //delete file by id
            unlink($getFilePath['file_location']);

            $query = DBconfig::getDBConnection()->prepare("DELETE FROM articles WHERE article_id =:id");
            $query->bindParam(":id", $_POST['article_id'], PDO::PARAM_INT);
            $result = $query->execute();

            header( "refresh:2;url=" . $_SERVER['PHP_SELF']);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
}

if(isset($_POST['add_new_post']) && $_FILES['new_post_filepath']['size'] > 0) {

    try {
        $query = DBconfig::getDBConnection()->prepare("INSERT INTO articles(file_location, posted_date, description) VALUES (:location, CURDATE(), :description)");
        $query->bindValue(":description", $_POST['new_post_description'], PDO::PARAM_STR);
        $query->bindValue(":location", "../files/". $_FILES['new_post_filepath']['name'], PDO::PARAM_STR);
        $query->execute();
        $a = move_uploaded_file($_FILES['new_post_filepath']['tmp_name'], "../files/". $_FILES['new_post_filepath']['name']);


        header("Location:" . $_SERVER['PHP_SELF']);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Редагування статей</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../stylesheet/posts_redaction.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">

</head>

<body>

<header class="container-fluid">
    <nav id="navigation">
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
                            echo "href=\"admin_page.php\"";
                        }
                        else {
                            echo "aria-disabled=\"true\"";
                        }
                        ?>
                    >Сторінка адміністратора</a>
                    <a href="edit_profile_page.php">Редагування та внесення данних</a>
                </li>

                <!--Photo and information-->
                <li class="col-xl-3  col-lg-3  nav_right">
                    <div class="card">
                        <div class="card-body d-flex flex-row justify-content-between align-items-center">
                            <img class="card-img-top" src="../images/<?= $sessionUser['image_file_name'];?>" alt="Відсутнє зображення">
                            <div class="text_inside_card">
                                <p class="card-text"><?php if(isset($sessionUser['surname']) && isset($sessionUser['name'])):?>
                                        <?= $sessionUser['surname'] . " " . $sessionUser['name'];?>
                                    <?php else: echo "Не вказані дані";?>
                                    <?php endif;?></p>
                                <p class="card-text"><?php if(isset($sessionUser['position'])):?>
                                        <?= $sessionUser['position'];?>
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

<main>

    <div class="container">

        <div class="row">

            <div class="col-sm-6 offset-sm-3 col-md-6 offset-md-3 col-lg-6 offset-lg-3">

                <form method="POST" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>" class="border border-dark rounded" id="add_new_post_form">

                        <div class="form-group">
                            <label for="descriptionOfPost">Опис публікації</label>
                            <input type="text" class="form-control" name="new_post_description" required minlength="3" id="descriptionOfPost" placeholder="Наприклад: Указ Президента про нагородження">
                        </div>

                        <div class="custom-file">
                            <label class="custom-file-label" for="customFile">Додайте файл (не більше 2мб)</label>
                            <input type="file" class="custom-file-input" id="customFile" name="new_post_filepath">
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary btn-block" name="add_new_post" id="add_new_post_button">Додати новий запис</button>
                        </div>

                </form>

            </div>

        </div>

        <a id="add_button" href="#"><img src="../images/add.svg"></a>

        <?php if(isset($postsList)): ?>
            <?php foreach($postsList as $post): ?>
                <article>

                        <form method="POST" action="<?= $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

                        <div class="row">

                                <input type="number" name="article_id" value="<?= $post['article_id']; ?>" hidden>

                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <small>Шлях до файлу</small>
                                    <input type="file" name="file" class="form-control-file" value="<?= $post['file_location']; ?>">
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <small>Опис</small>
                                    <input type="text" class="form-control" name="description" value="<?= $post['description']; ?>">
                                </div>

                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <small>Дата опублікування</small>
                                    <input type="date" class="form-control" name="posted_date" value="<?= $post['posted_date']; ?>">
                                </div>


                        </div>


                        <div class="row">

                        <div class="col-sm-6 offset-sm-3 col-md-6 offset-md-3 col-lg-6 offset-lg-3">

                            <div class="row flex-column align-items-center" id="<?= $post['article_id'] . "_row"?>">

                            <div class="btn-group" role="group"">
                                <button type="submit" class="btn btn-success" name="update">Оновити</button>
                                <button type="submit" class="btn btn-danger"  name="delete">Видалити</button>
                            </div>

                            </div>

                        </div>

                        </div>

                        </form>

                </article>
                <?php endforeach; ?>
                <?php else: echo "There is no any posts"; ?>
                <?php endif; ?>

                <hr>

    </div>

</main>


<div class="top_button">
    <a><img src="../images/up-arrow.png"></a>
</div>

</body>

<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/urljs/1.2.0/url.min.js"></script>

<script>
    window.onload = function() {
        $(document).ready(function () {
            bsCustomFileInput.init();
        });

        const id_value = Url.queryString("article_id");
        let currentElement = $(`#${id_value}_row`);
        let successAlertDiv = document.createElement("div");
        successAlertDiv.className = "alert alert-success";
        successAlertDiv.role = "alert";
        successAlertDiv.innerHTML = "Дані успішно змінено";
        successAlertDiv.style.textAlign= "center";
        currentElement.prepend(successAlertDiv);

        setTimeout(function(){
            jQuery(".alert-success").fadeOut("slow", function () {
                Url.updateSearchParam("updated");
                Url.updateSearchParam("article_id");
                Url.updateSearchParam(false);
            });
        }, 2000);

        //add button
        $('#add_button').click(function () {
            $('#add_new_post_form').css(
                {
                    "position": "relative",
                    "display": "block",
                    "top": "150px",
                }
                );
            $('article').css("visibility", "hidden");
        });

        $('#add_new_post_button').click(function () {
            $('#add_new_post_form').submit();
        });


        //top button
        $(window).on("scroll", () => {
                if(window.scrollY > 100) {
                    $(".top_button").css("visibility", "visible");
                } else {
                    $(".top_button").css("visibility", "hidden");
                }
            }
        );

        $(".top_button").on('click', () => window.scrollTo({
                top: 0,
                behavior: 'smooth',
            })
        );
    };




</script>
</html>