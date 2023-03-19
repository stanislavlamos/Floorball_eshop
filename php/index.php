<?php
    session_start();

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
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
    <div class="intro_section">
        <div class="intro_photo">
            <img src="../resources/intro_photo.png" alt="intro_photo" id="intro_photo">
        </div>
    </div>

    <div id="pros_section">
        <figure>
            <img src="../resources/delivery_icon.png" alt="delivery_icon">
            <figcaption>Doprava zdarma při nákupu nad 2000Kč</figcaption>
        </figure>

        <figure>
            <img src="../resources/return_icon.png" alt="return_icon">
            <figcaption>Zboží můžete vrátit do 30 dnů od objednání</figcaption>
        </figure>

        <figure>
            <img src="../resources/expedition_icon.png" alt="expedition_icon">
            <figcaption>Zboží expedujeme do 48 hodin od objednání</figcaption>
        </figure>
    </div>

    <div id="contact_section">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2560.5366891815115!2d14.416824415579546!3d50.07623787942547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470b94f3fcbb167d%3A0x9e906984c24d39b8!2sFakulta%20elektrotechnick%C3%A1%20%C4%8CVUT%20v%20Praze!5e0!3m2!1scs!2scz!4v1668449018681!5m2!1scs!2scz" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>

        <div class="intro_text">
            <h3>Adresa</h3>
            <p>Karlovo náměstí 1</p>
            <p>Praha 100, 47100</p>
            <br>
            <h3>Otevírací doba</h3>
            <p>Pondělí: 10 - 18h</p>
            <p>Úterý: 10 - 18h</p>
            <p>Středa: 10 - 18h</p>
            <p>Čvrtek: 10 - 18h</p>
            <p>Pátek: 10 - 18h</p>
            <p>Sobota: ZAVŘENO</p>
            <p>Nědele: ZAVŘENO</p>
        </div>
    </div>

</main>

<footer>
    <p>&#169;Florbal eshop by Stanislav Lamoš</p>
</footer>
</body>
</html>
