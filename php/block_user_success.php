<?php
session_start();

if (isset($_SESSION['sort-table'])){
    unset($_SESSION['sort-table']);
}

if (isset($_SESSION['category-table'])){
    unset($_SESSION['category-table']);
}

if(isset($_SESSION["user_blocked_success"]) and $_SESSION["user_blocked_success"] == 1){
    $_SESSION["user_blocked_success"] = 0;
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
            <h3 class='category_added_success'>Uživatel byl úspěšně zablokován</h3>
            <form typeof='get' name='delete_success_redirect' class='delete_success_redirect' action='profile.php'>
                <label for='back_to_profile_but_bu'></label>
                <input type='submit' value='Zpět na profil' id='back_to_profile_but_bu'>
            </form>
        ";
    ?>
</main>


<footer id="no_scroll_page">
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>
