<?php
require ('db_files/connection.php');

        session_start();
        if(!isset($_SESSION['login']) && !isset($_COOKIE['login'])) {
            header("Location: index.php");
        }
        //select info from DB about current user
        try {
            $PDO_connection = new PDO($dsn, $user, $password, $opt);
            $query = $PDO_connection->prepare("SELECT *
                  FROM workers
                  FULL JOIN users
                  ON user_id = users.id
                  WHERE users.login = :user_login");
            $query->bindValue('user_login', $_SESSION['login'], PDO::PARAM_STR);
            $query->execute();
            $sessionUser = $query->fetch(PDO::FETCH_ASSOC);
            $PDO_connection = null;
        } catch (PDOException $e) {
            echo "Error with content: " . $e->getMessage();
        }

        //update data in DB if user had click on button and image was changed
        if(isset($_POST['button_success']) && $_FILES['file']['size'] > 0) {

            $error = array();

            try{
                $PDO_connection = new PDO($dsn, $user, $password, $opt);
                $query_for_id_current_user = "SELECT id FROM users WHERE login = '{$_SESSION['login']}'";
                $query_for_id_current_user_get = $PDO_connection->query($query_for_id_current_user);
                $id_current_user = $query_for_id_current_user_get->fetch(PDO::FETCH_ASSOC);

                //check file type
                 if(exif_imagetype($_FILES['file']['tmp_name']) != IMAGETYPE_GIF &&
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

                     $error[] = "Недопустимий формат зображення, оберіть інший";

                    } else {
                     //write image to variable
                     $image = addslashes(file_get_contents($_FILES['file']['tmp_name']));
                     $queryForUpdateData =
                     "UPDATE workers SET
                     position  = '{$_POST['position']}',
                     dateOfBirth = '{$_POST['dateOfBirth']}',
                     rank = '{$_POST['rank']}',
                     tellNumber = '{$_POST['tellNumber']}',
                     surname = '{$_POST['surname']}',
                     name = '{$_POST['name']}',
                     image = '{$image}',
                     image_file_name = '{$_FILES['file']['name']}'   
                     WHERE user_id =" . $id_current_user['id'];
                     $PDO_connection->exec($queryForUpdateData);

                      $PDO_connection = null;
                      header("Location:" . $_SERVER['PHP_SELF']);
                 }


            } catch (PDOException $e) {
                echo "Error with content: " . $e->getMessage();
            }


            //update data in DB if user had click on button and image was not changed
            } elseif (isset($_POST['button_success']) && $_FILES['file']['size'] <= 0) {

            try{
                $PDO_connection = new PDO($dsn, $user, $password, $opt);
                $query_for_id_current_user = "SELECT id FROM users WHERE login = '{$_SESSION['login']}'";
                $query_for_id_current_user_get = $PDO_connection->query($query_for_id_current_user);
                $id_current_user = $query_for_id_current_user_get->fetch(PDO::FETCH_ASSOC);

                $queryForUpdateDataWithoutImage =
                "UPDATE workers SET
                position  = '{$_POST['position']}',
                dateOfBirth = '{$_POST['dateOfBirth']}',
                rank = '{$_POST['rank']}',
                tellNumber = '{$_POST['tellNumber']}',
                surname = '{$_POST['surname']}',
                name = '{$_POST['name']}'
                WHERE user_id =" . $id_current_user['id'];

                $PDO_connection->exec($queryForUpdateDataWithoutImage);
                $PDO_connection = null;
                header("Location:" . $_SERVER['PHP_SELF']);
            } catch (PDOException $e) {
                echo "Error with content: " . $e->getMessage();
            }
        }
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <title>Адміністративна панель</title>

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

            <link href="stylesheet/edit_profile.css" rel="stylesheet">

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
                        <li class="col-xl-4 col-lg-4 nav_center">
                            <a href="index.php">Головна</a>
                            <a href="admin.php">Сторінка адміністратора</a>
                            <a href="edit_profile.php">Редагування та внесення данних</a>
                        </li>

                        <!--Photo and information-->
                        <li class="col-xl-3 offset-xl-2 col-lg-5 offset-lg-1 nav_right">
                            <div class="card">
                                <div class="card-body d-flex flex-row justify-content-between align-items-center">
                                    <img class="card-img-top" src="data:image/jpg;base64,<?php echo base64_encode($sessionUser['image'])?>" alt="Card image">
                                    <div class="text_inside_card">
                                        <p class="card-title"><b><?php echo $sessionUser['surname'] . " " . $sessionUser['name'];?></b></p>
                                        <p class="card-text"><b><?php echo $sessionUser['position'];?></b></p>
                                    </div>
                                </div>
                                <a href="logout.php" class="btn btn-dark">Вийти</a>
                            </div>
                        </li>
                </ul>
            </nav>
        </header>

        <div class="container main_block">

            <div class="row">

                <div class="col-sm-12 col-md-12 col-lg-12">

                    <h3 class="text-center">Інформація про працівника</h3>

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

                    <div  class="text-center">
                        <div id="upload_file">
                        <p class="text-center"><b>Фото</b></p>
                    <input type="file" id="file" name="file">
                        <br>
                        <br>
                        <p class="text-success text_for_file"><small>Оберіть файл для оновлення профілю (рекомендований розмір 125х125)</small></p>
                            <p class="text-danger"><?php if(isset($error)) {echo $error[0]; unset($error);}?></p>
                        </div>
                    </div>

                    <div id="success_button">
                        <button type="submit" class="btn btn-success" name="button_success">Оновити</button>
                    </div>

                    </form>

                </div>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        </body>
        </html>
