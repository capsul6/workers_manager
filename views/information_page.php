<?php
require "../src/db_files/DB_config.php";
session_start();

if(empty($_SESSION['login']) && empty($_COOKIE['login'])) {
    header("Location: index.php");
}

//connect to db and get information based on Session "login" value for user account
try {
$connection = new DB_config("root", "");
$query = $connection->getDBConnection()->prepare("SELECT e.name, e.surname, e.image, e.position, e.image_file_name, e.permission_type
FROM workers e
LEFT JOIN users a
ON e.user_id = a.id
WHERE a.login = :user_login");
$query->bindValue(':user_login', $_SESSION['login'], PDO::PARAM_STR);
$query->execute();
$sessionUser = $query->fetch(PDO::FETCH_ASSOC);
$connection = null;
} catch (PDOException $e) {
echo "Error with content: " . $e->getMessage();
}
//get all posts from DB


/*
Pagination logic
*/
//define count of posts in one page
$countOfPostsPerPage = 20;
//we define and set count of pages and current count of posts in DB to zero
$countOfPages = $countOfPostsInDB = 0;
//get count of posts from DB
try {
    $connection = new DB_config("root", "");
    $countOfPostsInDB = $connection->getDBConnection()->query("SELECT COUNT(article_id) FROM articles WHERE posted_date > '2018-01-01'")->fetchColumn();
    $connection = null;
} catch (PDOException $e) {
    echo "Error with content: " . $e->getMessage();
}


//if sum of posts in DB equal to 20 then page will render only one page
if($countOfPostsInDB <= 20) {
    $countOfPages = 1;
} else {
//if sum of posts in DB more than 20 then get count of pages
    $countOfPages = $countOfPostsInDB/$countOfPostsPerPage;
    //if number is decimal with comma then adding 0.5 and rounding it
    if(is_double($countOfPages)) {
        $countOfPages += 0.5;
        $countOfPages = round($countOfPages, 0 , PHP_ROUND_HALF_DOWN);
    }
}

//we defining an array of pages and filling it
$pagination = array();
for($i = 0; $i < $countOfPages; $i++) {
    $pagination[$i] = $i+1;
}

if(empty($_GET['page'])) {
    try {
        $connection = new DB_config("root", "");
        $listOfPostsDependOnPage = $connection->getDBConnection()->query(
            "SELECT file_location, description, posted_date FROM articles WHERE posted_date > '2018-01-01' ORDER BY posted_date DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
        $connection = null;
    } catch (Exception $e){
        echo "Error with content: " . $e->getMessage();
    }

}

if(isset($_GET['page'])) {
    if($_GET['page'] == 1) {
        try {
            $connection = new DB_config("root", "");
            $listOfPostsDependOnPage = $connection->getDBConnection()->query(
                "SELECT file_location, description, posted_date FROM articles WHERE posted_date > '2018-01-01' LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
            $connection = null;
        } catch (Exception $e){
            echo "Error with content: " . $e->getMessage();
        }
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

        try {
            $connection = new DB_config("root", "");
            $listOfPostsDependOnPage = $connection->getDBConnection()->query(
                "SELECT file_location, description, posted_date FROM articles WHERE posted_date > '2018-01-01' LIMIT 20 OFFSET " . $offsetValue . ";")->fetchAll(PDO::FETCH_ASSOC);
            $connection = null;
        } catch (Exception $e){
            echo "Error with content: " . $e->getMessage();
        }

    }
}

function ReturnFiveArrayLinks(Array $IncomeArray) : Array {

    //initialize new empty array
    $ArrayWithFiveElements = array();

    //if array of posts contains only one page or if total amount of elements less than 5 then we render it without any changes
    if (count($IncomeArray) == 1 || count($IncomeArray) <= 5) {

        return $IncomeArray;

    } elseif (count($IncomeArray) > 5) {

        if (current($IncomeArray) < 3) {
            for ($i = 0; $i < 5; $i++) {
                $ArrayWithFiveElements[$i] = $i + 1;
            }
            return $ArrayWithFiveElements;
        }

        //we create correct rendering of last elements if count of elements > 5
        if( (current($IncomeArray) + 2) > count($IncomeArray) ) {

            $number = (current($IncomeArray) + 2) - count($IncomeArray);

            for($i = 0; $i < 5; $i++) {
                if($number == 1){
                    $ArrayWithFiveElements[$i] = current($IncomeArray) + $i - 3;

                } elseif ($number == 2){
                    $ArrayWithFiveElements[$i] = current($IncomeArray) + $i - 4;

                }
            }

        } else {
            for ($i = 0; $i < 5; $i++) {
                $ArrayWithFiveElements[$i] = current($IncomeArray) + $i - 2;
            }
        }

        return $ArrayWithFiveElements;
    }
}

function ReturnPageNumber(Array $currentPage) : Array {

        $buttons_array = array('prev_page' => 0, 'next_page' => 0);

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

    <link href="../web-inf/stylesheet/information_page.css" rel="stylesheet">
    <link href="../web-inf/images/favicon.ico" rel="shortcut icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="UTF-8">

</head>

<body>

<?php include_once "navigation_panel/header.php";?>

<main>

<div class="container">

    <?php

    if( (isset($_SESSION['login']) || isset($_COOKIE['login']) ) && $sessionUser['permission_type'] == "admin"): ?>
        <?php  echo "<a id=\"add_button\" href=\"posts_redaction.php\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Додати новий пост\"><img src=\"../web-inf/images/add.svg\"></a>"; ?>
        <?php  else: echo "";?>
    <?php endif;?>

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
            <object>
            <embed type="<?= mime_content_type($post['file_location']); ?>" src="<?= $post['file_location']; ?>">
            </object>
            <p><a href="<?= $post['file_location']; ?>" download>скачати документ</a></p>
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
            <?php foreach (ReturnFiveArrayLinks($pagination) as $value): ?>
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
    <a><img src="../web-inf/images/up-arrow.png"></a>
</div>

</body>

<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>

    $(function() {


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

            if(getParameterByName(page, "page") == null) {
                b[0].parentElement.setAttribute("class", "page-item active");
                b[0].style.backgroundColor = "black";
                b[0].style.borderColor = "black";
            } else if (innerTEXT.innerHTML == getParameterByName(page, "page")) {
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
            $('[data-toggle="tooltip"]').tooltip();
        });

    });


</script>
</html>