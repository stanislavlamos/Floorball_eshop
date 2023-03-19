<?php

    include_once "product_utils.php";
    include_once "category_utils.php";

    session_start();

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
    }

    $products = loadProducts();
    $product_problems = array();
    $name_problem = false;
    $product_price_problem = false;
    $product_text_problem = false;
    $file_problems = false;

    if (!(isset($_SESSION) and isset($_SESSION["user"]) and $_SESSION["user"]["isAdmin"])){
        http_response_code(403);
        header('Location: profile.php');
        die();
    }

    if (isset($_POST["product_name"], $_POST["category_list"], $_POST["product_price"], $_POST["color_picker_1"], $_POST["color_picker_2"], $_POST["color_picker_3"], $_POST["product_text"], $_FILES["product_picture"], $_SESSION["csfr_token"], $_POST["csfr_token"]) and $_POST["csfr_token"] == $_SESSION["csfr_token"]){
        $product_name = clear_product_string_input($_POST["product_name"]);
        $category_list = $_POST["category_list"];
        $product_price = $_POST["product_price"];
        $product_picture = $_FILES["product_picture"];
        $color_picker_1 = $_POST["color_picker_1"];
        $color_picker_2 = $_POST["color_picker_2"];
        $color_picker_3 = $_POST["color_picker_3"];
        $product_text = clear_product_string_input($_POST["product_text"]);

        if (strlen($product_name) < 10 or strlen($product_name) > 50){
            $product_problems[] = "Nesprávná délka jména produktu";
            $name_problem = true;
        }

        if (!empty(findProductByName($product_name, $products))){
            $product_problems[] = "Produkt s tímto jménem již existuje";
            $name_problem = true;
        }

        if($product_price < 100 or $product_price > 100000){
            $product_problems[] = "Nevalidní hodnota ceny";
            $product_price_problem = true;
        }

        if (strlen($product_text) < 20 or strlen($product_text) > 300){
            $product_problems[] = "Nesprávná délka textu produktu";
            $product_text_problem = true;
        }

        if($product_picture["size"] > 500000){
            $product_problems[] = "Nahraný soubor je příliš velký";
            $file_problems = true;
        }

        $picture_file_type = strtolower(pathinfo(basename($product_picture["name"]),PATHINFO_EXTENSION));
        if($picture_file_type != "jpg" and $picture_file_type != "png" and $picture_file_type != "jpeg"){
            $product_problems[] = "Nevalidní formát obrázku";
            $file_problems = true;
        }

        if (count($product_problems) == 0){
            $new_product_id = findProductMaxIndex($products) + 1;
            $categories = loadCategories();
            $chosen_category = findCategory($category_list, $categories);
            $picture_path = "./product_pictures/" . $new_product_id . "." . $picture_file_type;

            move_uploaded_file($_FILES["product_picture"]["tmp_name"], $picture_path);

            $new_product = array(
                "id" => $new_product_id,
                "name" => $product_name,
                "category_id" => $chosen_category["id"],
                "product_price" => intval($product_price),
                "product_picture_path" => $picture_path,
                "color_1" => $color_picker_1,
                "color_2" => $color_picker_2,
                "color_3" => $color_picker_3,
                "product_text" => $product_text
            );

            if (empty($products)){
                $products = array();
            }
            array_push($products, $new_product);
            saveProducts($products);
            header('Location: profile.php');
            die();
        }
    }
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/validate_add_product.js"></script>
    <meta charset="utf-8">
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>


<main id="reg_main">
    <div class="reg-form">
        <form class="reg-table" method="post" action="add_product.php" enctype="multipart/form-data" onsubmit="return validate_add_product();">
            <div class="reg-title">
                <h1>Přidání produktu</h1>
            </div>

            <div class="reg_label"><label for="product_name">Jméno*</label><br></div>
            <div class="reg_field"><input type="text" class="<?php if ($name_problem){echo "product_name_red";}?>" id="product_name" name="product_name" required value="<?php if(isset($_POST['product_name'])){ echo htmlspecialchars($_POST['product_name']);}?>"><br></div>

            <div class="reg_label"><label for="product_picture">Obrázek*</label><br></div>
            <div class="reg_field"><input type="file" class="<?php if ($file_problems){echo "product_picture_red";}?>" id="product_picture" name="product_picture" required><br></div>

            <div class="reg_label"><label for="category_list">Kategorie*</label><br></div>
            <select name="category_list" id="category_list" size="1" class="add_product_category_select">
                <?php
                    $categories = loadCategories();

                if (!empty($categories)){
                    foreach ($categories as $cur_category){
                        $cur_category_name = htmlspecialchars($cur_category["name"]);

                        if (isset($category_list) and $cur_category_name == $category_list){
                            echo "
                                <option value=\"$cur_category_name\" selected>$cur_category_name</option>    
                            ";
                            continue;
                        }

                        echo "
                            <option value=\"$cur_category_name\">$cur_category_name</option>    
                        ";
                    }
                } else {
                        $categories = array();
                        $new_category = array(
                            "id" => 0,
                            "name" => "default category"
                        );

                        array_push($categories, $new_category);
                        saveCategories($categories);

                        echo "
                            <option value=\"default category\">default category</option>    
                        ";
                    }
                ?>
            </select>

            <div class="reg_label"><label for="product_price">Cena v KČ*</label><br></div>
            <div class="reg_field"><input type="number" min="100" max="100000" class="<?php if ($product_price_problem){echo "product_price_red";}?>" id="product_price" name="product_price" value="<?php if(isset($_POST['product_price'])){ echo htmlspecialchars($_POST['product_price']);}?>" required><br></div>

            <div class="reg_label"><label for="color_picker_1">Barva 1*</label><br></div>
            <div class="reg_field"><input type="color" id="color_picker_1" name="color_picker_1" value="<?php if(isset($_POST['color_picker_1'])){ echo htmlspecialchars($_POST['color_picker_1']);}?>"><br></div>

            <div class="reg_label"><label for="color_picker_2">Barva 2*</label><br></div>
            <div class="reg_field"><input type="color" id="color_picker_2" name="color_picker_2" value="<?php if(isset($_POST['color_picker_2'])){ echo htmlspecialchars($_POST['color_picker_2']);}?>"><br></div>

            <div class="reg_label"><label for="color_picker_3">Barva 3*</label><br></div>
            <div class="reg_field"><input type="color" id="color_picker_3" name="color_picker_3" value="<?php if(isset($_POST['color_picker_3'])){ echo htmlspecialchars($_POST['color_picker_3']);}?>"><br></div>

            <div class="reg_label"><label for="product_text">Popis*</label><br></div>
            <div class="reg_field"><textarea class="<?php if ($product_text_problem){echo "product_text_red";}?>" id="product_text" name="product_text" rows="3" cols="50" required><?php if(isset($_POST['product_text'])){ echo htmlspecialchars($_POST['product_text']);}?></textarea><br></div>

            <input type="hidden" name="csfr_token" value="<?php echo $_SESSION['csfr_token'];?>">

            <label for="add_product_submit"></label>
            <div class="reg_submit"><input type="submit" id="add_product_submit" name="add_product_submit" value="Přidat produkt"></div>
        </form>
    </div>

    <div class="form_requirements_reg">
        <div class="reg_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
            <ul>
                <li>Pole označená hvězdičkou jsou povinná</li>
                <li>Text popisu musí mít 20 - 300 znaků</li>
                <li>Jméno produktu musí mít 10 - 50 znaků</li>
                <li>Cena musí být mezi 100-100000 KČ</li>
                <li>Obrázek musí být ve formátu jpg, jpeg nebo png</li>
                <li>Obrázek nesmí být větší než 5MB</li>
            </ul>
        </div>

        <?php
        if (count($product_problems) > 0){
            echo "<div class=\"problem_cond\">";
            echo "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>";
            echo "<ul>";

            foreach ($product_problems as $cur_problem){
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
