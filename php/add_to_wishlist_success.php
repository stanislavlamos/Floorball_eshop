<?php
    session_start();

if(!isset($_SESSION['user']) or (isset($_SESSION['user']) and $_SESSION['user']['isAdmin'])){
    header('Location: profile.php');
    die;
}

if (!isset($_SESSION['visiting_product_id'])){
    header('Location: profile.php');
    die;
}

if (isset($_SESSION['user']) and !$_SESSION['user']['isApproved']){
    header('Location: logout.php');
    die();
}

if (isset($_SESSION['user'], $_SESSION['visiting_product_id'], $_SESSION['added_wishlist']) and $_SESSION['added_wishlist'] == 1 and !$_SESSION['user']['isAdmin']){
    $_SESSION['added_wishlist'] = 0;
}

else {
    header('Location: profile.php');
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

<main>
    <?php
    echo "
            <h3 class='category_added_success'>Produkt byl úspěšně přidán do seznamu přání</h3>
            <form typeof='get' name='delete_success_redirect' class='delete_success_redirect' action='product.php'>
                <label for='wishlist_back_sub'></label>
                <input type='submit' value='Zpět na stránku produktu' id='wishlist_back_sub'>
            </form>
        ";
    ?>
</main>

<footer id="no_scroll_page">
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>

