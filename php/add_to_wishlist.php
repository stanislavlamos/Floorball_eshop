<?php
    include_once "utils.php";

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

    if (isset($_SESSION['user'], $_GET['wihlist_adder_submit'], $_SESSION['visiting_product_id']) and !$_SESSION['user']['isAdmin']){
        $other_accs = load_accounts_except_email($_SESSION['user']['email'], loadAccounts());

        if (empty($other_accs)){
            $other_accs = array();
        }

        if (!in_array($_SESSION['visiting_product_id'], $_SESSION['user']['wishlist'])){
            $_SESSION['user']['wishlist'][] = $_SESSION['visiting_product_id'];
        }

        array_push($other_accs, $_SESSION['user']);
        saveAccounts($other_accs);
        header('Location: add_to_wishlist_success.php');
        $_SESSION['added_wishlist'] = 1;
        die();
    }
?>