<?php
    include_once "utils.php";

/**
 * Function to load products from products' JSON file
 * @return array - array of products
 */
    function loadProducts(): array {
        if (!file_exists("../db_files/products.json")) {
            file_put_contents("../db_files/products.json", '[]');
        }
        return json_decode(file_get_contents("../db_files/products.json"), true);
    }

/**
 * Function to load products from user's wishlist
 * @param $product_ids_wishlist - products' ids from user's wishlist
 * @param $all_products - array of all products from JSON file
 * @return array - array of products from user's wishlist
 */
    function loadProductsFromWishlist($product_ids_wishlist, $all_products): array {
        if (empty($product_ids_wishlist) or empty($all_products)){
            return array();
        }

        $products = array();
        foreach ($product_ids_wishlist as $cur_id) {
            $cur_product = findProductById($cur_id, loadProducts());

            if (!empty($cur_product)){
                $products[] = $cur_product;
            }
        }
        return $products;
    }

/**
 * Function to load products from JSON file except product id given in the argument
 * @param $id - id of the product to extract
 * @return array - array of products except product with id given in the argument
 */
function loadProductsExceptId($id): array{
        $products = loadProducts();
        $filtered_product = array();

        foreach ($products as $unfiltered_product){
            if ($unfiltered_product["id"] != $id){
                $filtered_product[] = $unfiltered_product;
            }
        }
        return $filtered_product;
    }

/**
 * Function to find product by name in JSON file of products
 * @param $name - name of desired product
 * @param $products - all products saved in JSON file
 * @return array|null - product or null coresponding to given name
 */
function findProductByName($name, $products): ?array {
        if (empty($products)){
            return null;
        }

        foreach($products as $product) {
            if ($product["name"] == $name) {
                return $product;
            }
        }

        return null;
    }

/**
 * Function to find product by id
 * @param $id - id of the desired product
 * @param $products - all products from JSON file
 * @return array|null - product corresponding to given id
 */
function findProductById($id, $products): ?array {
        if (empty($products)){
            return null;
        }

        foreach($products as $product) {
            if ($product["id"] == $id) {
                return $product;
            }
        }

        return null;
    }

/**
 * Function to save products to JSON file
 * @param $products - array of products
 * @return void
 */
function saveProducts($products) {
        $fileContent = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents("../db_files/products.json", $fileContent);
    }

/**
 * Clear string input from form submission
 * @param $data - string to clear
 * @return string - cleared string
 */
function clear_product_string_input($data): string {
        $data = stripslashes(trim($data));

        return $data;
    }

/**
 * Find current max index of the product
 * @param $products - array of products from JSON file
 * @return int - current max index or -1
 */
function findProductMaxIndex($products): int {
        if (empty($products)){
            return -1;
        }

        $cur_maxindex = -10;
        foreach ($products as $product){
            if($product["id"] > $cur_maxindex){
                $cur_maxindex = $product["id"];
            }
        }
        return $cur_maxindex;
    }

/**
 * Function to get products from given category id
 * @param $unfiltered_products - array of unfiltered products
 * @param $cat_id - desired category id
 * @return array|null - array of found products
 */
function filterProductsFromCategory($unfiltered_products, $cat_id): ?array {
        if (empty($unfiltered_products)){
            return null;
        }

        $filtered_products = array();
        foreach ($unfiltered_products as $unfiltered_product) {
            if ($unfiltered_product['category_id'] == $cat_id){
                $filtered_products[] = $unfiltered_product;
            }
        }
        return $filtered_products;
    }

/**
 * Function to sort products according to given criteria
 * @param $products - array of products from JSON file
 * @param $criterion - criteria to sort by
 * @return array|null - array of sorted products
 */
function sortProducts($products, $criterion): ?array {
        if (empty($products)){
            return null;
        }

        if ($criterion == "expensive"){
            usort($products, function($a, $b) {
                return $a['product_price'] - $b['product_price'];
            });

            return array_reverse($products);
        }

        else if ($criterion == "cheap"){
            usort($products, function($a, $b) {
                return $a['product_price'] - $b['product_price'];
            });

            return $products;
        }

        else if ($criterion == "alphabet"){
            usort($products, 'stringSortByName');

            return $products;
        }

        return null;
    }

/**
 * Helper function to sort by string
 * @param $x - first string to compare
 * @param $y - second string to compare
 * @return int - returned comparison code
 */
function stringSortByName($x, $y) {
        return strcasecmp($x['name'], $y['name']);
    }

/**
 * Function to delete category by id
 * @param $category_id_to_delete - id of the category to delete
 * @return void
 */
function deleteProductsFromCategory($category_id_to_delete){
        if (empty(loadProducts())){
            return;
        }

        $products = loadProducts();
        $new_products = array();
        foreach ($products as $product){
            if ($product['category_id'] != $category_id_to_delete){
                $new_products[] = $product;
            }else {
                deleteProductsFromWishList($product['id']);
            }
        }
        saveProducts($new_products);
    }


