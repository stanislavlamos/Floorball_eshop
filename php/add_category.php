<?php

    include_once "category_utils.php";

    session_start();

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
    }

    $knownCategory = false;
    $problems_arr = array();

    if (!(isset($_SESSION["user"]) and $_SESSION["user"]["isAdmin"])){
        http_response_code(403);
        header('Location: profile.php');
        die();
    }

    if (isset($_POST["category_text"])){
        $name = clear_category_string_input($_POST["category_text"]);
        $categories = loadCategories();
        $category = findCategory($name, $categories);

        if(!empty($category)){
            $problems_arr[] = "Kategorie již existuje, zadejte jiný název";
            $knownCategory = true;
        }

        else if(strlen($name) < 5 or strlen($name) > 50){
            $problems_arr[] = "Délka názvu kategorie není správná";
            $knownCategory = true;
        }

        else {
            $new_category = array(
                "id" => findCategoryMaxIndex(loadCategories()) + 1,
                "name" => $name
            );

            if (empty($categories)){
                $categories = array();
            }

            array_push($categories, $new_category);
            saveCategories($categories);
            $_SESSION["category_added"] = 1;
            header('Location: add_category_success.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/validate_add_category.js"></script>
    <meta charset="utf-8">
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>

<main id="addcat_main">
    <div class="addcat-form">
        <div class="addcat-title">
            <h1>Přidání kategorie</h1>
        </div>
        <form class="addcat-table" method="post" action="add_category.php" onsubmit="return validate_add_category();">
            <div class="category_label"><label for="category_text">Název kategorie*</label><br></div>
            <div class="category_field_text"><textarea id="category_text" class="<?php if ($knownCategory){echo "category_text_red";}?>" name="category_text" rows="1" cols="50" required><?php if(isset($_POST['category_text'])){ echo htmlspecialchars($_POST['category_text']);}?></textarea><br></div>

            <label for="addcat_submit_type"></label>
            <div class="addcat_submit"><input type="submit" id="addcat_submit_type" name="addcat_submit_type" value="Přidat kategorii"></div>
        </form>
    </div>

    <div class="form_requirements_addcat">
        <div class="addcat_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
            <ul>
                <li>Pole označená hvězdičkou jsou povinná</li>
                <li>Název kategorie musí být unikátní</li>
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
