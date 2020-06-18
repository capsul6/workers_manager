<?php
require_once('../src/db_files/DB_config.php');

        session_start();
        if(!isset($_SESSION['login']) && !isset($_COOKIE['login'])) {
            header("Location: index.php");
        }

        //getting info from DB about current user
        try {
            $connection = new DB_config("root", "");
            $query = $connection->getDBConnection()->prepare("SELECT *
                  FROM workers
                  FULL JOIN users
                  ON user_id = users.id
                  WHERE users.login = :user_login");
            $query->bindValue(':user_login', $_SESSION['login'], PDO::PARAM_STR);
            $query->execute();
            $sessionUser = $query->fetch(PDO::FETCH_ASSOC);
            $connection = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        //getting list of information about when current user went out of work and return to work
        try{
            $connection = new DB_config("root", "");
            $queryForDateComeDateReturnCurrentUser = $connection->getDBConnection()->prepare("SELECT e.outside_id, e.date_come, e.date_return, e.outside_type
                FROM outside_records e
                LEFT JOIN workers w
                ON e.worker_id = w.worker_id
                WHERE e.worker_id = :id
                ORDER BY e.date_come ASC
                ");

            $queryForDateComeDateReturnCurrentUser->bindValue(":id", $sessionUser['worker_id'], PDO::PARAM_INT);
            $queryForDateComeDateReturnCurrentUser->execute();
            $queryForDateComeDateReturnCurrentUserResult = $queryForDateComeDateReturnCurrentUser->fetchAll(PDO::FETCH_ASSOC);
            $connection = null;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        //update data in DB if user had click on button and image was changed
        if (isset($_POST['button_success']) && $_FILES['file']['size'] > 0) {

                //checking file type
                 if (exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_GIF &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_JPEG &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_PNG &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_SWF &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_PSD &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_BMP &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_TIFF_II &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_TIFF_MM &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_JPC &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_JP2 &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_JPX &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_JB2 &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_SWC &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_IFF &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_WBMP &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_XBM &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_ICO &&
                     exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_WEBP) {

                     $error = "Недопустимий формат зображення, оберіть інший";

                    } else {

                try {

                //write image to variable
                $image = addslashes(file_get_contents($_FILES['file']['tmp_name']));

                $connection = new DB_config("root", "");
                //updating information that was changed by user
                $forUpdateWorkerValues = $connection->getDBConnection()->prepare("UPDATE workers SET
                     position  = ?,
                     dateOfBirth = ?,
                     rank = ?,
                     tellNumber = ?,
                     surname = ?,
                     name = ?,
                     image = ?,
                     image_file_name = ?
                     WHERE user_id = ?");

                $forUpdateWorkerValues->execute(array($_POST['position'], $_POST['dateOfBirth'], $_POST['rank'],
                                                $_POST['tellNumber'], $_POST['surname'], $_POST['name'], $image,
                                                $_FILES['file']['name'], $sessionUser['user_id']));

                //save file in specific directory
                $filesDirectory = __DIR__ . "../images/" . $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $filesDirectory);
                $connection = null;

            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            header("Location:" . $_SERVER['PHP_SELF']);
        }



         //update data in DB if user had click on the button and new image was not inserted
        } elseif (isset($_POST['button_success']) && $_FILES['file']['size'] <= 0) {

            try{
                $connection = new DB_config("root", "");
                $forUpdateWorkerValues = $connection->getDBConnection()->prepare("UPDATE workers SET
                     position  = ?,
                     dateOfBirth = ?,
                     rank = ?,
                     tellNumber = ?,
                     surname = ?,
                     name = ?
                     WHERE user_id = ?");

                $forUpdateWorkerValues->execute(array($_POST['position'], $_POST['dateOfBirth'], $_POST['rank'],
                $_POST['tellNumber'], $_POST['surname'], $_POST['name'], $sessionUser['user_id']));

                $connection = null;

            header("Location:" . $_SERVER['PHP_SELF']);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        //add new outside_activity with typed parameters to the current user outside activity history
        if(isset($_GET['addNew_outside_activity'])) {

            try{
                $connection = new DB_config("root", "");
                $forAddNewOutsideActivity = $connection->getDBConnection()->prepare("
                INSERT INTO outside_records (date_come, date_return, worker_id, outside_type)
                VALUES (:date_come, :date_return, :worker_id, :outside_type);
                ");
                $forAddNewOutsideActivity->bindParam(":date_come", $_GET['add_new_date_come']);
                $forAddNewOutsideActivity->bindParam(":date_return", $_GET['add_new_date_return']);
                $forAddNewOutsideActivity->bindParam(":worker_id", $sessionUser['worker_id']);
                $forAddNewOutsideActivity->bindParam(":outside_type", $_GET['add_new_type_of_outside_activity']);

                $forAddNewOutsideActivity->execute();

                $connection = null;

                header("Location:" . $_SERVER['PHP_SELF']);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }

        }

        //delete outside_activity that was typed
        if(isset($_GET['delete_outside_activity'])) {
            try{
                $connection = new DB_config("root", "");
                $forDeleteOutsideActivity = $connection->getDBConnection()->prepare("
                DELETE FROM outside_records WHERE outside_id = :outside_id ;
                ");
                $forDeleteOutsideActivity->bindParam(":outside_id", $_GET['outside_id']);

                $forDeleteOutsideActivity->execute();

                $connection = null;

                header("Location:" . $_SERVER['PHP_SELF']);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        //update outside_activity that was typed
        if(isset($_GET['update_outside_activity'])) {
        try{

        $connection = new DB_config("root", "");
        $forUpdateOutsideActivityForCurrentUser = $connection->getDBConnection()->prepare("
                UPDATE outside_records
                SET outside_type = :outside_type, date_come = :date_come, date_return = :date_return
                WHERE outside_id = :outside_id ;
                ");
        $forUpdateOutsideActivityForCurrentUser->bindParam(":outside_id", $_GET['outside_id']);
        $forUpdateOutsideActivityForCurrentUser->bindParam(":outside_type", $_GET['current_outside_type_update']);
        $forUpdateOutsideActivityForCurrentUser->bindParam(":date_come", $_GET['current_date_come_update']);
        $forUpdateOutsideActivityForCurrentUser->bindParam(":date_return", $_GET['current_date_return_update']);

        $forUpdateOutsideActivityForCurrentUser->execute();

        $connection = null;

        header("Location:" . $_SERVER['PHP_SELF']);

        } catch (PDOException $e) {
        echo $e->getMessage();
        }
        }

        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <title>Введення та зміна інформації</title>

            <link href="../web-inf/images/favicon.ico" rel="shortcut icon">

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

            <link href="../web-inf/stylesheet/edit_profile_page.css" rel="stylesheet">

            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <meta charset="UTF-8">
        </head>

        <body>

        <header class="container-fluid">
            <nav>
                <ul>
                    <div class="row">
                        <!--logo-->
                        <li class="col-xl-3 col-lg-3 nav_left"><a href="index.php"><img src="../web-inf/images/Webp.net-resizeimage.jpg" alt="logo"/></a></li>
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
                                ?>>Сторінка адміністратора</a>
                            <a href="edit_profile_page.php">Редагування та внесення данних</a>
                        </li>

                        <!--Photo and information-->
                        <li class="col-xl-3  col-lg-3  nav_right">
                            <div class="card">
                                <div class="card-body d-flex flex-row justify-content-between align-items-center">
                                    <img class="card-img-top" src="../web-inf/images/<?= $sessionUser['image_file_name']?>" alt="Відсутнє зображення">
                                    <div class="text_inside_card text-center">
                                        <p class="card-text">
                                            <?php if (isset($sessionUser['surname']) && isset($sessionUser['name'])):?>
                                            <?= $sessionUser['surname'] . " " . $sessionUser['name'];?>
                                            <?php else:
                                                echo "Не вказані дані";
                                            ?>
                                            <?php endif;?></p>
                                        <p class="card-text"><?php if(isset($sessionUser['position'])):?>
                                                <?= $sessionUser['position'];?>
                                            <?php else:
                                                echo "Не вказані дані";
                                            ?>
                                            <?php endif;?>
                                        </p>
                                    </div>
                                </div>
                                <!--Buttons with logout and edit profile actions-->
                                <a href="edit_profile_page.php" class="btn btn-primary btn-sm">Редагувати профіль</a>
                                <a href="logout_page.php" class="btn btn-dark btn-sm">Вийти</a>
                            </div>
                        </li>
                </ul>
            </nav>
        </header>

        <div class="container main_block">

            <div class="row">

                <div class="col-sm-12 col-md-12 col-lg-12">

                    <h4 class="text-center">Особиста інформація про працівника</h4>

                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">

                    <table class="table table-hover">

                        <thead class="thead-dark">
                        <tr>
                            <th>Посада</th>
                            <th>Дата народження</th>
                            <th>Звання</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td><input type="text" name="position" class="form-control" value="<?php if(!empty($sessionUser['position'])) {
                                                                     echo $sessionUser['position'];
                                                                     }?>"></td>
                            <td><input type="date" name="dateOfBirth" class="form-control" value="<?php if(!empty($sessionUser['dateOfBirth'])) {
                                                                     echo $sessionUser['dateOfBirth'];
                                                                     }?>"></td>
                            <td><input type="text" name="rank" class="form-control" value="<?php if(!empty($sessionUser['rank'])) {
                                                                    echo $sessionUser['rank'];
                                }?>"></td>
                        </tr>
                        </tbody>

                        <thead class="thead-dark">
                        <tr>
                            <th>Телефон</th>
                            <th>Прізвище</th>
                            <th>Ім'я</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td><input type="tel" name="tellNumber" class="form-control" value="<?php if(!empty($sessionUser['tellNumber'])) {
                                    echo $sessionUser['tellNumber'];
                                }?>"></td>
                            <td><input type="text" name="surname" class="form-control" value="<?php if(!empty($sessionUser['surname'])) {
                                    echo $sessionUser['surname'];
                                }?>"></td>
                            <td><input type="text" name="name" class="form-control" value="<?php if(!empty($sessionUser['name'])) {
                                    echo $sessionUser['name'];
                                }?>"></td>
                        </tr>
                        </tbody>

                    </table>

                    <div class="text-center">
                        <div id="upload_file">
                    <label for="file" class="photo_label text-center"><b>Фото</b></label>
                    <input type="file" id="file" name="file">
                        <br>
                        <br>
                        <p class="text-success text_for_file"><small>Оберіть файл для оновлення профілю (рекомендований розмір 125х125, не більше 2мб)</small></p>
                            <p class="text-danger"><?php if(isset($error)) {
                                echo $error; unset($error);
                            }?></p>
                        </div>
                    </div>

                    <div class="text-center">
                    <button type="submit" class="btn btn-success" name="button_success">Оновити</button>
                    </div>

                    </form>


                    <br>

                    <h4 class="text-center">Хронологія та види відсутності</h4>

                    <table class="table table-hover">

                    <thead class="thead-dark">
                    <tr>
                        <th>Вид відсутності</th>
                        <th>Період з</th>
                        <th>Період по </th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>

                    <form method="get" action="<?php $_SERVER['PHP_SELF']?>" name="form_for_dateCome_dateReturn_update" id="form_for_dateCome_dateReturn_update">

                    <?php if(isset($queryForDateComeDateReturnCurrentUserResult)){
                        foreach($queryForDateComeDateReturnCurrentUserResult as $thisValues):?>
                        <tr>
                            <input type="hidden" value="<?= $thisValues['outside_id'] ?>" name="outside_id">
                            <td>
                                <select class="custom-select" name="current_outside_type_update" id="current_outside_type_update">
                                    <option value="<?= $thisValues['outside_type']?>" disabled selected><?= $thisValues['outside_type']?></option>
                                    <option value="відпустка">відпустка</option>
                                    <option value="відрядження">відрядження</option>
                                    <option value="лікарняний">лікарняний</option>
                                </select>
                            </td>
                            <td><input type="date" class="form-control"  name="current_date_come_update" value="<?php echo $thisValues['date_come']?>"></td>
                            <td><input type="date" class="form-control"  name="current_date_return_update" value="<?php echo $thisValues['date_return']?>"></td>
                            <td class="text-center">
                                <button type="submit" class="btn btn-warning update" name="update_outside_activity">Оновити</button>
                                <button type="submit" class="btn btn-danger delete" name="delete_outside_activity">Видалити</button>
                            </td>
                        </tr>
                        <?php endforeach;
                        }?>

                    </form>

                    <form method="get" action="<?= $_SERVER['PHP_SELF']?>" name="form_for_dateCome_dateReturn_add">

                        <tr>
                            <td><select type="text" class="form-control custom-select" name="add_new_type_of_outside_activity" required>
                                    <option value="відпустка">відпустка</option>
                                    <option value="відрядження">відрядження</option>
                                    <option value="лікарняний">лікарняний</option>
                            </select></td>
                            <td><input type="date" class="form-control" name="add_new_date_come" required></td>
                            <td><input type="date" class="form-control" name="add_new_date_return" required></td>
                            <td class="text-center">
                            <button type="submit" class="btn btn-success" name="addNew_outside_activity">Додати</button>
                            </td>
                        </tr>
                    </form>

                    </tbody>

                    </table>

                </div>

            </div>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script>
            $(document).ready(function (){
               $("#form_for_dateCome_dateReturn_update").on("submit",function () {
                   let a = $("#current_outside_type_update").val();
                   console.log(a);
                });
            });
        </script>
        </body>
        </html>
