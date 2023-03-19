<?php
include_once "utils.php";

session_start();

if (isset($_SESSION['sort-table'])){
    unset($_SESSION['sort-table']);
}

if (isset($_SESSION['category-table'])){
    unset($_SESSION['category-table']);
}

$problems_arr = array();
$all_accounts = onlyDisapprovedAccounts(loadAccountsWithoutAdmin(loadAccounts()));
$user_problem = false;

if (!isset($_SESSION['user']) or (isset($_SESSION['user']) and !$_SESSION['user']['isAdmin'])){
    header('Location: profile.php');
    die();
}

if (isset($_POST) and isset($_POST["user_to_unblock"], $_SESSION['user']) and $_SESSION['user']['isAdmin']) {
    $user_to_unblock_email = $_POST["user_to_unblock"];

    if (empty(findAccount($user_to_unblock_email, $all_accounts))){
        $problems_arr[] = "Uživatel neexistuje, obnovte stránku";
        $user_problem = true;
    }

    if(!empty(findAccount($user_to_unblock_email, $all_accounts)) and (findAccount($user_to_unblock_email, $all_accounts)['isApproved'])){
        $problems_arr[] = "Uživatel je již odblokován";
        $user_problem = true;
    }

    if (count($problems_arr) == 0){
        $account_to_edit = findAccount($user_to_unblock_email, $all_accounts);
        $account_to_edit['isApproved'] = true;
        $unedited_accounts = load_accounts_except_email($user_to_unblock_email, loadAccounts());

        if (empty($unedited_accounts)){
            $unedited_accounts = array();
        }

        array_push($unedited_accounts, $account_to_edit);
        saveAccounts($unedited_accounts);
        $_SESSION['user_unblocked_success'] = 1;
        header('Location: unblock_user_success.php');
        die();
    }
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

<main id="log_main">
    <div class="signin-form">
        <div class="signin-title">
            <h1>Odblokování uživatele</h1>
        </div>
        <form class="unblock-user-table" method="post" action="unblock_user.php">

            <div class="reg_label"><label for="user_to_unblock">Email uživatele*</label><br></div>
            <select name="user_to_unblock" id="user_to_unblock" size="1" class="<?php if ($user_problem){echo "user_to_unblock_red";} else{echo "add_product_category_select";}?>">
                <?php
                foreach ($all_accounts as $account){
                    if (isset($_POST['user_to_unblock']) and $_POST['user_to_unblock'] == $account['email']){
                        echo "<option value=\"" .htmlspecialchars($account['email']) . "\" selected>" . htmlspecialchars($account['email']) . "</option>";
                        continue;
                    }
                    echo "<option value=\"" .htmlspecialchars($account['email']) . "\">" . htmlspecialchars($account['email']) . "</option>";
                }
                ?>
            </select>

            <label for="unblock_user_submit"></label>
            <div class="signin_submit"><input type="submit" id="unblock_user_submit" name="unblock_user_submit" value="Odblokovat uživatele"></div>
        </form>
    </div>

    <div class="form_requirements_log">
        <div class="log_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
            <ul>
                <li>Pole označená hvězdičkou jsou povinná</li>
            </ul>
        </div>

        <?php
        if (count($problems_arr) > 0){
            echo "<div class=\"problem_cond\">";
            echo "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>";
            echo "<ul>";

            foreach ($problems_arr as $cur_problem){
                echo "<li>" . $cur_problem . "</li>";
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