<?php
    include_once "product_utils.php";
    include_once "category_utils.php";
    include_once "comment_utils.php";
    $COMMENTS_PER_PAGE = 3;

    session_start();

    $cur_page = 0;

    if(isset($_SESSION['comment_page'])){
        $cur_page = $_SESSION['comment_page'];
    }

    if (isset($_GET['page_comment'])){
        $cur_page = intval($_GET['page_comment']);
    }   else {
        $cur_page = $_GET['page_comment'] = 0;
    }
    $_SESSION['comment_page'] = $cur_page;

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
    }

    if (isset($_GET['desired_product_id'])) {
        $_SESSION["visiting_product_id"] = intval($_GET['desired_product_id']);
    }

    if (!isset($_SESSION["visiting_product_id"])){
        header('Location: product_gallery.php');
        die();
    }

    $cur_product_id = $_SESSION["visiting_product_id"];
    $products = loadProducts();

    if (empty(findProductById($cur_product_id, $products))){
        header('Location: product_gallery.php');
        die();
    }

    $product = findProductById($cur_product_id, $products);
    $categories = loadCategories();
    $category = findCategoryById($product["category_id"], $categories);

    if (empty($category)){
        header('Location: product_gallery.php');
        die();
    }
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <meta charset="utf-8">

        <!--Tady bohužel nevím, jak vyhovet podmince, aby veskere styly byly ve zvlastnim filu a zaroven mohl pouzit promenne z PHP-->
        <style>
        .product-color .color-picker .color1 {
                background-color: <?php echo $product["color_1"] . ";";?>
        }
        </style>

        <style>
            .product-color .color-picker .color2 {
                background-color: <?php echo $product["color_2"] . ";";?>
            }
        </style>

        <style>
            .product-color .color-picker .color3 {
                background-color: <?php echo $product["color_3"] . ";";?>
            }
        </style>
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>

<main>
    <div class="product-container">
        <div class="one_product">
            <div class="my_row">
                <div class="my_column">
                    <div class="product_image">
                        <img src="<?php echo $product['product_picture_path']?>" alt="product_main_picture" id="product_main_picture">
                    </div>
                </div>

                <div class="my_column">
                    <div class="product-category">
                        <h5><?php echo "#" . htmlspecialchars($category['name'])?></h5>
                    </div>

                    <div class="product_details">
                        <div class="product_title">
                            <h1><?php echo htmlspecialchars($product['name'])?></h1>
                        </div>
                    </div>

                    <div class="product-price">
                        <h3><?php echo htmlspecialchars($product['product_price']) . " Kč"?></h3>
                    </div>

                    <div class="product-color">
                        <h3>Barva</h3>

                        <div class="color-picker">
                            <input type="radio" name="color" id="color1"  value="color1" class="color-input">
                            <label for="color1" class="color1"></label>

                            <input type="radio" name="color" id="color2" value="color2" class="color-input">
                            <label for="color2" class="color2"></label>

                            <input type="radio" name="color"  id="color3" value="color3" class="color-input">
                            <label for="color3" class="color3"></label>
                        </div>
                    </div>

                    <div class="product-description">
                        <h3>Popis</h3>
                        <p><?php echo $product['product_text']?></p>
                    </div>

                    <div class="wishlist-adder">

                        <?php
                            if (isset($_SESSION, $_SESSION["user"]) and $_SESSION["user"]["isAdmin"]){
                                echo "<form action=\"delete_product.php\" method=\"get\" class=\"profile_action_button\" id=\"delete_product_special_button\">
                                        <label for='delete_product_submit'></label>
                                        <input type='submit' value='Odstranit produkt' id='delete_product_submit' name='delete_product_submit'>
                                      </form>

                                    <form action=\"edit_product.php\" method=\"get\" class=\"profile_action_button\">
                                    <label for='edit_prod_sub'></label>
                                    <input type='submit' value='Editovat produkt' id='edit_prod_sub'>
                                    </form>";
                            }

                            else if (isset($_SESSION, $_SESSION["user"]) and $_SESSION["user"]["isApproved"] and !$_SESSION["user"]["isAdmin"]){
                                echo "<form action=\"add_to_wishlist.php\" method=\"get\" class=\"profile_action_button\">
                                    <label for='wihlist_adder_submit'></label>
                                    <input type='submit' id='wihlist_adder_submit' name='wihlist_adder_submit' value='Přidat do seznamu přání'>
                                    </form>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h1 id="discussion_heading">Diskuze</h1>

    <?php
        if (isset($_SESSION, $_SESSION["user"]) and $_SESSION["user"]["isApproved"] and !$_SESSION["user"]["isAdmin"]){
                                echo "<form action=\"add_comment.php\" method=\"get\" class=\"profile_action_button\">
                                    <label for='add_dis_submit'></label>
                                    <input type='submit' id='add_dis_submit' value='Přidat příspěvek'>
                                    </form>";
        }
    ?>

    <?php
        $comments = array_reverse(findCommentByProductId($cur_product_id, loadComments()));
        $cur_page_comments = array_slice($comments, $cur_page * $COMMENTS_PER_PAGE, $COMMENTS_PER_PAGE);

    if (empty($cur_page_comments) or empty($comments)){
            echo "<h3 id='no_comments_dis'>Diskuze neobsahuje žádné příspěvky</h3>";
        } else {
            foreach ($cur_page_comments as $comment){
                echo "
                    <div class=\"comment_container\">
                    <p><span>" . htmlspecialchars($comment['author_email']) . "</span></p>
                    <p>" . htmlspecialchars($comment['comment_header']) . "</p>
                    <p>" . htmlspecialchars($comment['comment_text']) . "</p>";

                    if(isset($_SESSION['user']) and $_SESSION['user']['isAdmin'] and empty($comment['reply_header']) and empty($comment['reply_text'])){
                        echo "
                            <form action=\"add_reply.php\" method=\"get\" class=\"profile_action_button\">
                                <label for='add_reply_submit'></label>
                                <input type='submit' id='add_reply_submit' value='Přidat odpověď'>
                                <input type='hidden' name='reply_comment_id' value=\"" . $comment['id'] . "\">
                            </form>";
                    }

                    echo "</div>";

                if (!empty($comment['reply_header']) or !empty($comment['reply_text'])){
                    echo "
                    <div class=\"reply_container\">
                    <p><span>Admin</span></p>
                    <p>" . htmlspecialchars($comment['reply_header']) . "</p>
                    <p>" . htmlspecialchars($comment['reply_text']) . "</p>
                    </div>";
                }
            }
        }
    ?>

    <?php
        if (count($comments) > 0){
            $remaining_comments = count($comments) - $COMMENTS_PER_PAGE * ($cur_page + 1);

            echo "<div class=\"product_page-header\">";
            if ($cur_page > 0){
                echo "<form method=\"get\" action=\"product.php\">
                        <label for='previous_page'></label>
                        <input type=\"submit\" value=\"Předchozí stránka\" id=\"previous_page\">
                        <input type=\"hidden\" name=\"page_comment\" value=\"" . ($cur_page - 1) . "\">
                    </form>";
            }

            if($remaining_comments > 0){
                echo "<form method=\"get\" action=\"product.php\">
                        <label for='next_page_submit'></label>
                        <input type=\"submit\" value=\"Další stránka\" id='next_page_submit'>
                        <input type=\"hidden\" name=\"page_comment\" value=\"" . ($cur_page + 1) . "\">
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
