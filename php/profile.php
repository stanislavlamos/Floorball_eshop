<?php
    include_once "utils.php";

    session_start();

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
    }

    if (!(isset($_SESSION) and isset($_SESSION["user"]) and $_SESSION["user"]["isApproved"])){
        http_response_code(403);
        header('Location: logout.php');
        die();
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

<main class="profile_main">
    <div class="profile_info">
        <h1 class="profile_title">
            <?php
                if (isset($_SESSION) and $_SESSION["user"]){
                    if ($_SESSION["user"]["isAdmin"] and $_SESSION["user"]["isApproved"]){
                        echo "Administrátor";
                    } else{
                        echo "Uživatel";
                    }
                }
            ?>
        </h1>

        <?php
            if (isset($_SESSION) and $_SESSION["user"]){
                if (isset($_SESSION["user"]["firstName"]) and !empty($_SESSION["user"]["firstName"])){
                    $cur_value = htmlspecialchars($_SESSION['user']["firstName"]);
                    echo "<h6 class=\"given_field_label\">Jméno</h6>
                         <h3 class=\"field_value\">$cur_value</h3><br>";
                }

                if (isset($_SESSION["user"]["lastName"]) and !empty($_SESSION["user"]["lastName"])){
                    $cur_value = htmlspecialchars($_SESSION['user']["lastName"]);
                    echo "<h6 class=\"given_field_label\">Příjmení</h6>
                         <h3 class=\"field_value\">$cur_value</h3><br>";
                }

                if (isset($_SESSION["user"]["birthDate"]) and !empty($_SESSION["user"]["birthDate"])){
                    $cur_value = htmlspecialchars($_SESSION['user']["birthDate"]);
                    echo "<h6 class=\"given_field_label\">Datum narození</h6>
                         <h3 class=\"field_value\">$cur_value</h3><br>";
                }

                if (isset($_SESSION["user"]["email"]) and !empty($_SESSION["user"]["email"])){
                    $cur_value = htmlspecialchars($_SESSION['user']["email"]);
                    echo "<h6 class=\"given_field_label\">Email</h6>
                         <h3 class=\"field_value\">$cur_value</h3><br>";
                }

                if (isset($_SESSION["user"]["isApproved"]) and !empty($_SESSION["user"]["isApproved"])){
                    $cur_value = htmlspecialchars($_SESSION['user']["isApproved"]);

                    if ($cur_value){
                        echo "<h6 class=\"given_field_label\">Stav účtu</h6>
                         <h3 class=\"field_value\">Povolen</h3><br>";
                    } else{
                        echo "<h6 class=\"given_field_label\">Stav účtu</h6>
                         <h3 class=\"field_value\">Zablokován</h3><br>";
                    }
                }
            }
        ?>
    </div>

    <div class="profile_actions">
        <form action="logout.php" method="get" class="profile_action_button">
            <label for="logout_sub_but"></label>
            <input type='submit' value='Odhlásit se' id="logout_sub_but">
        </form>

        <?php
            if(isset($_SESSION["user"]) and $_SESSION["user"]["isAdmin"]){
                echo "<form action=\"add_product.php\" method=\"get\" class=\"profile_action_button\">
                    <label for='add_product_sub'></label>
                    <input type='submit' value='Přidat produkt' id='add_product_sub'>
                    </form>
                    
                    <form action=\"add_category.php\" method=\"get\" class=\"profile_action_button\">
                    <label for='add-cat_sub'></label>
                    <input type='submit' value='Přidat kategorii' id='add-cat_sub'>
                    </form>
                    
                    <form action=\"delete_category.php\" method=\"get\" class=\"profile_action_button\">
                    <label for='delete_prod_sub'></label>
                    <input type='submit' value='Odstranit kategorii' id='delete_prod_sub'>
                    </form>

                    <form action=\"unblock_user.php\" method=\"get\" class=\"profile_action_button\">
                    <label for='approve_accs_sub'></label>
                    <input type='submit' value='Povolit účty' id='approve_accs_sub'>
                    </form>
                    
                    <form action=\"block_user.php\" method=\"get\" class=\"profile_action_button\">
                    <label for='block_accs_sub'></label>
                    <input type='submit' value='Blokovat účty' id='block_accs_sub'>
                    </form>";
            }
        ?>

        <?php
            if(isset($_SESSION["user"]) and $_SESSION["user"]["isApproved"] and !$_SESSION["user"]["isAdmin"]){
                echo "<form action=\"wish_list.php\" method=\"get\" class=\"profile_action_button\">
                        <label for='wishlist_sub_but'></label>
                        <input type='submit' value='Seznam přání' id='wishlist_sub_but'>
                    </form>";
            }
        ?>
    </div>
</main>

<footer id="no_scroll_page">
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>