<?php
/**
 * Function to load all accounts stored in JSON file
 * @return array - accounts retrieved from JSON file
 */
function loadAccounts(): array {
        if (!file_exists("../db_files/accounts.json")) {
            file_put_contents("../db_files/accounts.json", '[]');
        }
        return json_decode(file_get_contents("../db_files/accounts.json"), true);
    }

/**
 * Function to find account by unique email
 * @param $email - email to find user by
 * @param $accounts - array of all users
 * @return array|null - found user
 */
function findAccount($email, $accounts): ?array {
        if (empty($accounts)){
            return null;
        }

        foreach($accounts as $account) {
            if ($account["email"] == $email) {
                return $account;
            }
        }
        return null;
    }

/**
 * Function to retrieve only disapproved accounts
 * @param $accounts - all accounts stored in JSON file
 * @return array|null - array of disapproved accounts
 */
function onlyDisapprovedAccounts($accounts): ?array {
        if (empty($accounts)){
            return null;
        }

        $disapproved_accs = array();
        foreach ($accounts as $account){
            if (!$account['isApproved']){
                $disapproved_accs[] = $account;
            }
        }
        return $disapproved_accs;
    }

/**
 * Function to retrieve approved accounts
 * @param $accounts - array of all accounts
 * @return array|null - approved accounts
 */
function onlyApprovedAccounts($accounts): ?array {
        if (empty($accounts)){
            return null;
        }

        $disapproved_accs = array();
        foreach ($accounts as $account){
            if ($account['isApproved']){
                $disapproved_accs[] = $account;
            }
        }
        return $disapproved_accs;
    }

/**
 * Function to load accounts without admin
 * @param $accounts - array of all accounts
 * @return array|null - non admin accounts
 */
function loadAccountsWithoutAdmin($accounts): ?array {
        if (empty($accounts)){
            return null;
        }

        $acc_without_admin = array();
        foreach ($accounts as $account){
            if (!$account['isAdmin']){
                $acc_without_admin[] = $account;
            }
        }
        return $acc_without_admin;
    }

/**
 * Load all accounts except the one with the given email
 * @param $email - email of the account to left out
 * @param $accounts - all accounts from JSON file
 * @return array|null - all accounts except the one with the given email
 */
function load_accounts_except_email($email, $accounts): ?array {
        if (empty($accounts) or empty(findAccount($email, $accounts))){
            return null;
        }

        $accs = array();
        foreach ($accounts as $account){
            if ($account['email'] != $email){
                $accs[] = $account;
            }
        }
        return $accs;
    }

/**
 * Function to save all accounts to JSON file
 * @param $accounts - accounts to save
 * @return void
 */
function saveAccounts($accounts) {
        $fileContent = json_encode($accounts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents("../db_files/accounts.json", $fileContent);
    }

/**
 * Function to clear input string
 * @param $data - string to clear
 * @return string - cleared string
 */
function clear_string_input($data): string {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }

/**
 * Function to delete products with given id from all wishlists
 * @param $product_id - id of the product to delete
 * @return void
 */
function deleteProductsFromWishList($product_id){
        if (empty(loadAccounts())){
            return;
        }

        $accs = loadAccounts();
        $new_accs = array();
        foreach ($accs as $acc){
            if (empty($acc['wishlist'])){
                $new_accs[] = $acc;
                continue;
            }

            $new_wishlist = array();
            foreach ($acc['wishlist'] as $ws_elem){
                if ($ws_elem != $product_id){
                    $new_wishlist[] = $ws_elem;
                }
            }

            $acc['wishlist'] = $new_wishlist;
            $new_accs[] = $acc;
        }

        saveAccounts($new_accs);
    }
