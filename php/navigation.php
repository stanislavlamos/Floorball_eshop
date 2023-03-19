<?php
    if (isset($_SESSION['user'])) {
        echo "
            <nav>
            <ul>
                <li><a  id=\"logo_nav\" href=\"index.php\"><h2>Florbal eshop</h2></a></li>
                <li><a href=\"product_gallery.php\">Produkty</a></li>
                <li><a href=\"index.php#contact_section\">Kontakt</a></li>
                <li><a href=\"about.php\">O nás</a></li>
                <li id = \"predposledni_prvek_signedin\"><a href=\"profile.php\">Profil</a></li>
            </ul>
            </nav>";
    } else {
        echo "
            <nav>
            <ul>
                <li><a  id=\"logo_nav\" href=\"index.php\"><h2>Florbal eshop</h2></a></li>
                <li><a href=\"product_gallery.php\">Produkty</a></li>
                <li><a href=\"index.php#contact_section\">Kontakt</a></li>
                <li><a href=\"about.php\">O nás</a></li>
                <li id = \"predposledni_prvek\"><a href=\"login.php\">Příhlášení</a></li>
                <li><a id=\"last_nav_element\" href=\"registration.php\">Registrace</a></li>
            </ul>
            </nav>";
    }