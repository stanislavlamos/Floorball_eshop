<?php

include_once "product_utils.php";
include_once "utils.php";
include_once "category_utils.php";

session_start();

if (isset($_SESSION['sort-table'])){
    unset($_SESSION['sort-table']);
}

if (isset($_SESSION['category-table'])){
    unset($_SESSION['category-table']);
}

$category_problem = false;
$problems_arr = array();

if (!isset($_SESSION['user']) or (isset($_SESSION['user']) and !$_SESSION['user']['isAdmin'])){
    header('Location: profile.php');
    die();
}

if (isset($_POST) and isset($_POST["category_name"], $_SESSION['user']) and $_SESSION['user']['isAdmin']) {
    $category_name = clear_category_string_input($_POST["category_name"]);

    if (strlen($category_name) < 5 or strlen($category_name) > 50){
        $problems_arr[] = "Název kategorie má nesprávnou délku";
        $category_problem = true;
    }

    if (empty(findCategory($category_name, loadCategories()))){
        $problems_arr[] = "Kategorie s tímto názvem neexistuje";
        $category_problem = true;
    }

    if (count($problems_arr) == 0){
        $cat_to_delete = findCategory($category_name, loadCategories());
        deleteProductsFromCategory($cat_to_delete['id']);
        deleteCategory($cat_to_delete['id']);
        $_SESSION['delete_category_success'] = 1;
        header('Location: delete_category_success.php');
        die();
    }
}
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/validate_delete_category.js"></script>
    <meta charset="utf-8">
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>

<main id="log_main">
    <div class="signin-form">
        <div class="signin-title">
            <h1>Odstranění kategorie</h1>
        </div>
        <form class="delete-cat-table" method="post" action="delete_category.php" onsubmit="return validate_delete_category();">
            <div class="signin_label"><label for="category_name">Jméno kategorie*</label><br></div>
            <div class="signin_field"><input type="text" id="category_name" class="<?php if ($category_problem){echo "category_name_red";}?>" name="category_name" required onkeyup="showHint(this.value)" value="<?php if(isset($_POST['category_name'])){ echo htmlspecialchars($_POST['category_name']);}?>"><br></div>
            <p>Návrhy: <span id="txtHint"></span></p>

            <label for="delete_cat_sub"></label>
            <div class="signin_submit"><input type="submit" id="delete_cat_sub" name="delete_cat_sub" value="Odstranit kategorii"></div>
        </form>
    </div>

    <div class="form_requirements_log">
        <div class="log_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
            <ul>
                <li>Pole označená hvězdičkou jsou povinná</li>
                <li>Název kategorie musí mezi 5-50 znaky</li>
            </ul>
        </div>

        <?php
        if (count($problems_arr) > 0){
            echo "<div class=\"problem_cond\">";
            echo "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>";
            echo "<ul>";

            foreach ($problems_arr as $cur_problem){
                echo "<li>$cur_problem</li>";
            }

            echo "</ul>";
            echo "</div>";
        }
        ?>
    </div>
</main>

<footer id="no_scroll_page">
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>