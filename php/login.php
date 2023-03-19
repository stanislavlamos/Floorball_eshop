<?php
    include_once "utils.php";

    session_start();

    if (!isset($_SESSION["csfr_token"])){
        $_SESSION["csfr_token"] = bin2hex(random_bytes(32));
    }

    if (isset($_SESSION['sort-table'])){
        unset($_SESSION['sort-table']);
    }

    if (isset($_SESSION['category-table'])){
        unset($_SESSION['category-table']);
    }

    $knownEmail = true;
    $correctPassword = true;
    $acc_approved = true;
    $problems_arr = array();

    if (isset($_POST) and isset($_POST["email_addr_signin"], $_POST["password_sign"], $_SESSION["csfr_token"], $_POST["csfr_token"]) and $_POST["csfr_token"] == $_SESSION["csfr_token"]) {
        $email = clear_string_input($_POST["email_addr_signin"]);
        $password = trim($_POST["password_sign"]);
        $accounts = loadAccounts();
        $account = findAccount($email, $accounts);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $problems_arr[] = "Nesprávný formát emailu";
            $knownEmail = false;
        }

        if(empty($account)){
            $problems_arr[] = "Nesprávný email, uživatel nenalezen";
        }

        if (!empty($account) and !$account["isApproved"]){
            $problems_arr[] = "Uživatel je nyní zablokován, kontaktujte administrátora";
        }

        if (!empty($account) and !password_verify($password, $account["password"])){
            $problems_arr[] = "Nesprávné heslo";
        }


        if (empty($account)) {
            $knownEmail = false;
        } else {
            if (password_verify($password, $account["password"])) {
                if ($account["isApproved"]){
                    $_SESSION["user"] = $account;
                    header('Location: index.php');
                    die();
                } else{
                    $acc_approved = false;
                }
            } else {
                $correctPassword = false;
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/validate_login.js"></script>
    <meta charset="utf-8">
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>

<main id="log_main">
    <div class="signin-form">
        <div class="signin-title">
            <h1>Přihlášení</h1>
        </div>
        <form class="signin-table" method="post" action="login.php" onsubmit="return validate_login();">
            <div class="signin_label"><label for="email_addr_signin">Email*</label><br></div>
            <div class="signin_field"><input type="email" pattern="^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" id="email_addr_signin" name="email_addr_signin" class="<?php if (!$knownEmail or !$acc_approved){echo "email_addr_signin_red";}?>" required value="<?php if(isset($_POST['email_addr_signin'])){ echo htmlspecialchars($_POST['email_addr_signin']);}?>"><br></div>

            <div class="signin_label"><label for="password_sign">Heslo*</label><br></div>
            <div class="signin_field"><input type="password" class="<?php if (!$correctPassword or !$acc_approved){echo "password_sign_red";}?>" id="password_sign" name="password_sign" required><br></div>

            <input type="hidden" name="csfr_token" value="<?php echo $_SESSION['csfr_token'];?>">

            <label for="login_submit"></label>
            <div class="signin_submit"><input type="submit" id="login_submit" name="login_submit" value="Přihlásit"></div>
        </form>
    </div>

    <div class="form_requirements_log">
        <div class="log_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
                <ul>
                    <li>Pole označená hvězdičkou jsou povinná</li>
                    <li>Email musí být validní a patřit již zaregistrovanému uživateli</li>
                    <li>Heslo musí korespondovat se zadaným emailem</li>
                </ul>
        </div>

        <?php
            if (count($problems_arr) > 0){
                echo "<div class=\"problem_cond\">";
                echo "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>";
                echo "<ul>";

                foreach ($problems_arr as $cur_problem){
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