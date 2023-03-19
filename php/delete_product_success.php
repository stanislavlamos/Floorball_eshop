<?php
session_start();

$delete_product_success = false;

if (isset($_SESSION['sort-table'])){
    unset($_SESSION['sort-table']);
}

if (isset($_SESSION['category-table'])){
    unset($_SESSION['category-table']);
}

if(isset($_SESSION["product_deleted"]) and $_SESSION["product_deleted"] == 1){
    $delete_product_success = true;
    $_SESSION["product_deleted"] = 0;
}else {
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
            <h3 class='category_added_success'>Produkt úspešně odstraněn</h3>
            <form typeof='get' name='delete_success_redirect' class='delete_success_redirect' action='product_gallery.php'>
                <label for='delete_prod_sub'></label>
                <input type='submit' value='Zpět na přehled produktů' id='delete_prod_sub'>
            </form>
        ";
    ?>
</main>


<footer id="no_scroll_page">
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>
