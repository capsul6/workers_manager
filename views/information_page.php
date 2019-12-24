<?php
require_once('../db_files/DBconfig.php');

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
$query = DBconfig::getDBConnection()->query("SELECT file_location, description, posted_date
FROM articles;");
$postsList = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
echo "Error with content: " . $e->getMessage();
}

/*
Pagination logic
*/
//define count of posts in one page
$countOfPostsPerPage = 20;
//we define and set count of pages and current count of posts in DB to zero
$countOfPages = $countOfPostsInDB = 0;
//get count of posts from DB
try {
    $countOfPostsInDB = DBconfig::getDBConnection()->query("SELECT COUNT(article_id) FROM articles WHERE posted_date > '2018-01-01'")->fetchColumn();
} catch (PDOException $e) {
    echo "Error with content: " . $e->getMessage();
}


//if count of posts in DB equal to 20 then page will renders only one page
if($countOfPostsInDB <= 20) {
    $countOfPages = 1;
} else {
//if count of posts in DB more than 20 then get count of pages
    $countOfPages = $countOfPostsInDB/$countOfPostsPerPage;
    //if number is decimal with comma then adding 0.5 and rounding it
    if(is_double($countOfPages)) {
        $countOfPages += 0.5;
        $countOfPages = round($countOfPages, 0 , PHP_ROUND_HALF_DOWN);
    }
}

//we are defining array of pages and filling it
$pagination = array();
for($i = 0; $i < $countOfPages; $i++) {
    $pagination[$i] = $i+1;
}

if(empty($_GET['page'])) {
    $listOfPostsDependOnPage = DBconfig::getDBConnection()->query(
        "SELECT file_location, description, posted_date FROM articles WHERE posted_date > '2018-01-01' LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
}

if(isset($_GET['page'])) {
    if($_GET['page'] == 1) {
        $listOfPostsDependOnPage = DBconfig::getDBConnection()->query(
            "SELECT file_location, description, posted_date FROM articles WHERE posted_date > '2018-01-01' LIMIT 20;")->fetchAll(PDO::FETCH_ASSOC);
    } elseif($_GET['page'] > 1) {
        //get posts from DB by url value
        $offsetValue = ($_GET['page'] - 1) * $countOfPostsPerPage;
        if(current($pagination) < $_GET['page']) {
            while (current($pagination) < $_GET['page']) {
                next($pagination);
            }
        } elseif (current($pagination) > $_GET['page']) {
            while (current($pagination) > $_GET['page']) {
                prev($pagination);
            }
        }

        $listOfPostsDependOnPage = DBconfig::getDBConnection()->query(
            "SELECT file_location, description, posted_date FROM articles WHERE posted_date > '2018-01-01' LIMIT 20 OFFSET " . $offsetValue . ";")->fetchAll(PDO::FETCH_ASSOC);

    }
}


function ReturnPageNumber(Array $currentPage) :array {

        $buttons_array = array('prev_page'=>0, 'next_page' => 0);

        if (current($currentPage) == 1) {
           $buttons_array['prev_page'] = 1;
           $buttons_array['next_page'] = 2;
           return $buttons_array;

        } elseif (current($currentPage) > 1 && current($currentPage) < array_slice($currentPage, -1)[0]) {
            $buttons_array['prev_page'] = current($currentPage) - 1;
            $buttons_array['next_page'] = current($currentPage) + 1;
            return $buttons_array;

        } elseif (current($currentPage) == array_slice($currentPage, -1)[0]) {
            $buttons_array['prev_page'] = current($currentPage) - 1;
            $buttons_array['next_page'] = current($currentPage);
            return $buttons_array;
        }
    }

?>
<!doctype html>
<html lang="en">
<head>
    <title>Головна сторінка</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../stylesheet/information.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">

</head>

<body>

<header class="container-fluid">
    <nav>
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
                            <img class="card-img-top" src="../images/<?php echo $sessionUser['image_file_name'];?>" alt="Відсутнє зображення">
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

    <?php if(  (isset($_SESSION['login']) || isset($_COOKIE['login'])) && ($_SESSION['login']) == "capsul6" || $_COOKIE['login'] == "capsul6") {
        echo "
        <a id=\"add_button\" href=\"posts_redaction.php\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Додати новий пост\"><img src=\"../images/add.svg\"></a>
        ";
    }?>

    <?php if(isset($listOfPostsDependOnPage)): ?>
    <?php foreach($listOfPostsDependOnPage as $post): ?>
    <article>
    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-12">

            <div class="row">
                <div class="col-lg-2 offset-lg-10 col-md-2 offset-md-10 col-sm-2 offset-sm-10">
                    <small class="bg-primary text-white rounded text-right">Додано <?= $post['posted_date'] ?></small>
                </div>
            </div>

            <div class="row">

            <div class="col-sm-6 col-md-6 col-lg-6">
            <embed type="<?= mime_content_type($post['file_location']); ?>" src="<?= $post['file_location']; ?>">
            <p><a href="<?= $post['file_location']; ?>" download="">скачати документ</a></p>
            <p><a href="<?= $post['file_location']; ?>" target="_blank">переглянути у новому вікні</a></p>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6">
            <p class="text-break"><?= $post['description']; ?></p>
            </div>

            </div>

        </div>

    </div>
    </article>
    <hr>
    <?php endforeach; ?>
    <?php else: echo "There is no any posts"; ?>
    <?php endif; ?>

</div>

</main>


<footer>

        <div class="container">
        <div class="row">
        <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-10 offset-lg-1">

        <form method="GET" action="<?= $_SERVER['PHP_SELF']; ?>">
        <ul class="pagination justify-content-center">
            <li class="page-item outer"><a class="page-link" href="<?= $_SERVER['PHP_SELF'] . "?page=" . ReturnPageNumber($pagination)['prev_page']; ?>">Попередня сторінка</a></li>
            <?php foreach ($pagination as $value): ?>
            <li class="page-item"><a class="page-link inner" href="<?= $_SERVER['PHP_SELF'] . "?page=" . $value; ?>"><?= $value; ?></a></li>
            <?php endforeach; ?>
            <li class="page-item outer"><a class="page-link" href="<?= $_SERVER['PHP_SELF'] . "?page=" . ReturnPageNumber($pagination)['next_page']; ?>">Наступна сторінка</a></li>
        </ul>
        </form>

        </div>
        </div>
        </div>

</footer>

<div class="top_button">
    <a><img src="../images/up-arrow.png"></a>
</div>

</body>

<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    window.onload = () => {
        let getParameterByName = (url, name) =>  {
            if ("URLSearchParams" in window) {
                //Browser supports URLSearchParams
                let searchParams = new URLSearchParams(url.split('?')[1]);
                //return searchParams.get(name);
                return searchParams.get(name)
            } else {
                document.write(
                    "Your browser currently doesn't support URLSearchParams. Switch to another browser."
                );
                return null;
            }
        };

        let inner = $(".inner").length;
        let outer = $(".outer");

        if(inner == 1) {
            outer.attr("class", "page-item outer disabled");
            for(let refer of outer) {
                refer.firstChild.href = "javascript:void(0)";
            }
        }


        let b = document.getElementsByClassName("page-link inner");
        for(let innerTEXT of b) {
           let page = window.location.href;
           if (innerTEXT.innerHTML == getParameterByName(page, "page")) {
                innerTEXT.parentElement.setAttribute("class", "page-item active");
                innerTEXT.style.backgroundColor = "black";
                innerTEXT.style.borderColor = "black";
           } else if(getParameterByName(page, "page") == null) {
                innerTEXT.parentElement.setAttribute("class", "page-item active");
                innerTEXT.style.backgroundColor = "black";
                innerTEXT.style.borderColor = "black";
           }
        }

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

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

    };


</script>
</html>