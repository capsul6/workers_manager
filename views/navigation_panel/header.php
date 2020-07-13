<header class="container-fluid">
    <nav>
        <ul>
            <div class="row">
                <!--logo-->
                <li class="col-xl-3 col-lg-3 nav_left"><a href="index.php"><img src="/ddz_info/web-inf/images/policeLogo.jpg" alt="logo"/></a></li>
                <!--navigation -->
                <li class="col-xl-6 col-lg-6 d-flex justify-content-center align-items-center nav_center">
                    <a href="information_page.php">Головна</a>
                    <a
                        <?php
                        if(isset($_SESSION['login']) && $sessionUser['permission_type'] == "admin" || isset($_COOKIE['login']) && $sessionUser['permission_type'] == "admin") {
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
                            <img class="card-img-top" src="../web-inf/images/<?php echo $sessionUser['image_file_name'];?>" alt="Відсутнє зображення">
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
                        <a href="logout_page.php" class="btn btn-dark btn-sm">Вийти</a>
                    </div>
                </li>
        </ul>
    </nav>
</header>