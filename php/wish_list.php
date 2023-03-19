<?php

include_once "category_utils.php";
include_once "product_utils.php";

session_start();

if (!isset($_SESSION['user']) or (isset($_SESSION['user']) and $_SESSION['user']['isAdmin'])){
    header('Location: profile.php');
    die();
}

if (isset($_SESSION['user']) and !$_SESSION['user']['isApproved']){
    header('Location: logout.php');
    die();
}

$all_categories = loadCategories();
$all_products = array_reverse(loadProductsFromWishlist($_SESSION['user']['wishlist'], loadProducts()));
$PRODUCTS_PER_PAGE = 12;

$cur_page = 0;

if (isset($_SESSION['sort-table'])){
    unset($_SESSION['sort-table']);
}

if (isset($_SESSION['category-table'])){
    unset($_SESSION['category-table']);
}

if(isset($_SESSION['product_page'])){
    $cur_page = $_SESSION['product_page'];
}

if (isset($_GET['page'])){
    $cur_page = intval($_GET['page']);
}else {
    $cur_page = $_GET['page'] = 0;
}
$_SESSION['product_page'] = $cur_page;


if (empty($all_products)){
    $all_products = array();
}
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <meta charset="utf-8">
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>

<main>
    <div class="category_title">
        <h1>Seznam přání</h1>
    </div>

    <?php
    $cur_page_products = array_slice($all_products, $cur_page * $PRODUCTS_PER_PAGE, $PRODUCTS_PER_PAGE);
    if (count($cur_page_products) > 0 and !empty($all_products)){
        echo "
                        <div class=\"product_tiles_block\">
                        <div class=\"product-tiles-container\">
                    ";
        foreach ($cur_page_products as $cur_page_product) {
            echo "
                                    <div class=\"single-product-tile\">
                                        <div class=\"mini_image\">
                                            <img src=\"" .htmlspecialchars($cur_page_product['product_picture_path']) .  "\" alt=\"product_tile_picture\" class=\"product_tile_picture\">
                                        </div>


                                        <div class=\"product-tile-title\">
                                            <h2>" . htmlspecialchars($cur_page_product['name']) . "</h2>
                                        </div>

                                        <div class=\"product-tile-price\">
                                            <h4>" . htmlspecialchars($cur_page_product['product_price']) . " Kč" . "</h4>
                                        </div>
                                        
                                        <div class=\"detail-viewer\">
                                            <form method='get' action='product.php'>
                                                <label for=\"view_detail_" . $cur_page_product['id'] . "\"></label>
                                                <input type='submit' value='Zobrazit detail' id=\"view_detail_" . $cur_page_product['id'] . "\">
                                                                  
                                                <input type='hidden' name='desired_product_id' value=\"" . $cur_page_product['id'] . "\" id=\"hidden_" . $cur_page_product['id'] . "\">
                                            </form>
                                        </div>
                                    </div>";
        }
        echo "
                        </div>
                        </div>";
    }else{
        echo "<h1 id='no_products'>Žádné produkty nejsou k zobrazení</h1>";
    }
    ?>


    <?php
    if (count($all_products) > 0){
        $remaining_products = count($all_products) - $PRODUCTS_PER_PAGE * ($cur_page + 1);

        echo "<div class=\"product_page-header\">";
        if ($cur_page > 0){
            echo "<form method=\"get\" action=\"product_gallery.php\">
                        <label for=\"previous_page\"></label>
                        <input type=\"submit\" value=\"Předchozí stránka\" id=\"previous_page\">
                        <input type=\"hidden\" name=\"page\" value=\"" . ($cur_page - 1) . "\">
                    </form>";
        }

        if($remaining_products > 0){
            echo "<form method=\"get\" action=\"product_gallery.php\">
                        <label for='next_page'></label>
                        <input type=\"submit\" value=\"Další stránka\" id='next_page'>
                        <input type=\"hidden\" name=\"page\" value=\"" . ($cur_page + 1) . "\">
                    </form>
                ";
        }
        echo "</div>";
    }
    ?>
</main>

<footer>
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>
