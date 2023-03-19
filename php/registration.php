<?php
    session_start();

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
    }

    if (!isset($_SESSION["csfr_token"])){
        $_SESSION["csfr_token"] = bin2hex(random_bytes(32));
    }

    include_once "utils.php";

    $problems_array = array();
    $email_exists = false;
    $password_mismatch = false;
    $first_name_problem = false;
    $last_name_problem = false;

    if (isset($_POST) and isset($_POST["email_addr"], $_POST["password_first"], $_POST["password_second"], $_SESSION["csfr_token"], $_POST["csfr_token"]) and $_POST["csfr_token"] == $_SESSION["csfr_token"]){
        $accounts = loadAccounts();
        $first_name = clear_string_input($_POST["first_name"]);
        $last_name = clear_string_input($_POST["last_name"]);
        $birth_date = clear_string_input($_POST["birth_date"]);
        $email = clear_string_input($_POST["email_addr"]);
        $password = trim($_POST["password_first"]);
        $repeat_password = trim($_POST["password_second"]);

        foreach($accounts as $account) {
            if ($account["email"] == $email) {
                $email_exists = true;
                $problems_array[] = "Zadaný email byl již použit";
                break;
            }
        }

        if (empty($email) and !filter_var($email, FILTER_VALIDATE_EMAIL) or strlen($email) > 100){
            $email_exists = true;
            $problems_array[] = "Email není validní";
        }

        if (strlen($first_name) > 20 or strpos($first_name, ' ') !== false){
            $first_name_problem = true;
            $problems_array[] = "Jméno má nesprávný formát";
        }

        if (strlen($last_name) > 20 or strpos($last_name, ' ') !== false){
            $last_name_problem = true;
            $problems_array[] = "Příjmení má nesprávný formát";
        }

        if (strlen($password) < 10){
            $password_mismatch = true;
            $problems_array[] = "Heslo je příliš krátké";
        }

        if (!preg_match('~[0-9]+~', $password)){
            $password_mismatch = true;
            $problems_array[] = "Heslo neobsahuje číslici";
        }

        if (!preg_match('/[A-Z]/', $password)){
            $password_mismatch = true;
            $problems_array[] = "Heslo neobsahuje velké písmeno";
        }

        if (strlen($password) > 20){
            $problems_array[] = "Heslo je příliš dlouhé";
            $password_mismatch = true;
        }

        if (count($problems_array) == 0 and !$email_exists) {
            if ($password == $repeat_password) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $new_account = array(
                    "firstName" => $first_name,
                    "lastName" => $last_name,
                    "birthDate" => $birth_date,
                    "wishlist" => array(),
                    "email" => $email,
                    "password" => $password_hash,
                    "isAdmin" => false,
                    "isApproved" => true
                );

                array_push($accounts, $new_account);
                saveAccounts($accounts);
                header('Location: login.php');
                die();
            } else {
                $password_mismatch = true;
                $problems_array[] = "Hesla se neshodují";
            }
        } else {
            if ($password != $repeat_password) {
                $password_mismatch = true;
                $problems_array[] = "Hesla se neshodují";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/validate_registration.js"></script>
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
        <form class="reg-table" method="post" name="reg_form" action="registration.php" onsubmit="return validate_registration();">
            <div class="reg-title">
                <h1>Registrace</h1>
            </div>

            <div class="reg_label"><label for="first_name">Jméno</label><br></div>
            <div class="reg_field"><input type="text" class="<?php if ($first_name_problem){echo "first_name_red";}?>" id="first_name" name="first_name" value="<?php if(isset($_POST['first_name'])){ echo htmlspecialchars($_POST['first_name']);} else {echo "";}?>"><br></div>

            <div class="reg_label"><label for="last_name">Přijmení</label><br></div>
            <div class="reg_field"><input type="text" class="<?php if ($last_name_problem){echo "last_name_red";}?>" id="last_name" name="last_name" value="<?php if(isset($_POST['last_name'])){ echo htmlspecialchars($_POST['last_name']);} else {echo "";}?>"><br></div>

            <div class="reg_label"><label for="birth_date">Datum narození</label><br></div>
            <div class="reg_field"><input type="date" id="birth_date" name="birth_date" value="<?php if(isset($_POST['birth_date'])){ echo htmlspecialchars($_POST['birth_date']);} else {echo "";}?>"><br></div>

            <div class="reg_label"><label for="email_addr">Email*</label><br></div>
            <div class="reg_field"><input type="email" pattern="^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" class="<?php if ($email_exists){echo "email_addr_red";}?>" id="email_addr" name="email_addr" required value="<?php if(isset($_POST['email_addr'])){ echo htmlspecialchars($_POST['email_addr']);} else {echo "";}?>"><br></div>

            <div class="reg_label"><label for="password_first">Heslo*</label><br></div>
            <div class="reg_field"><input type="password" class="<?php if ($password_mismatch){echo "password_first_red";}?>" id="password_first" name="password_first" required value=""><br></div>

            <div class="reg_label"><label for="password_second">Heslo znovu*</label><br></div>
            <div class="reg_field"><input type="password" class="<?php if ($password_mismatch){echo "password_second_red";}?>" id="password_second" name="password_second" required value=""><br><br></div>

            <input type="hidden" name="csfr_token" value="<?php echo $_SESSION['csfr_token'];?>">

            <label for="reg_submit"></label>
            <div class="reg_submit"><input id="reg_submit" name="reg_submit" type="submit" value="Registrovat"></div>

        </form>
    </div>

    <div class="form_requirements_reg">
        <div class="reg_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
            <ul>
                <li>Pole označená hvězdičkou jsou povinná</li>
                <li>Jméno a Přijmení mohou mít každý maximálně 20 znaků</li>
                <li>Heslo má 10 - 20 znaků a musí obsahovat číslice a velká písmena</li>
                <li>Hesla se musí shodovat</li>
            </ul>
        </div>

        <?php
            if (!empty($problems_array) and count($problems_array) > 0){
                echo "<div class=\"problem_cond\">";
                echo "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>";
                echo "<ul>";

                foreach ($problems_array as $cur_problem){
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
