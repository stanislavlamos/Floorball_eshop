<?php
/**
 * Function to load categories from stored JSON file
 * @return array - categories retrieved from JSON file
 */
function loadCategories(): array {
        if (!file_exists("../db_files/categories.json")) {
            file_put_contents("../db_files/categories.json", '[]');
        }
        return json_decode(file_get_contents("../db_files/categories.json"), true);
    }

/**
 * Function to find category by name from the categories stored in JSON file
 * @param $name - name of the category to find
 * @param $categories - categories retrieved from JSON file
 * @return array|null - array with found category
 */
function findCategory($name, $categories): ?array {
        if (empty($categories)){
            return null;
        }

        foreach($categories as $category) {
            if ($category["name"] == $name) {
                return $category;
            }
        }
        return null;
    }

/**
 * Function to find category by its id
 * @param $id - id of the desired category
 * @param $categories - array of categories stored in JSON file
 * @return array|null - retrieved category based on given id
 */
function findCategoryById($id, $categories): ?array {
        if (empty($categories)){
            return null;
        }

        foreach($categories as $category) {
            if ($category["id"] == $id) {
                return $category;
            }
        }
        return null;
    }

/**
 * Function to save categories to JSON
 * @param $categories - array of categories to save
 * @return void
 */
function saveCategories($categories) {
        $fileContent = json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents("../db_files/categories.json", $fileContent);
    }

/**
 * Function to clear string input
 * @param $data - input string to clear
 * @return string - cleared input string
 */
function clear_category_string_input($data): string
    {
        $data = trim($data);

        return $data;
    }

/**
 * Function to retrieve names of all categories saved in JSON file
 * @return array - array of category names
 */
function getAllCategoryNames(): array{
        $all_categories = loadCategories();

        if (empty($all_categories)){
            return array();
        }

        $names = array();
        foreach ($all_categories as $cur_category){
            $names[] = $cur_category['name'];
        }

        return $names;
    }

/**
 * Function to delete category from JSON based on its id
 * @param $category_id - id of the category to delete
 * @return void
 */
function deleteCategory($category_id){
        $all_categories = loadCategories();

        if (empty($all_categories)){
            return;
        }

        $new_categories = array();
        foreach ($all_categories as $new_category){
            if($new_category['id'] != $category_id){
                $new_categories[] = $new_category;
            }
        }

        saveCategories($new_categories);
    }

/**
 * Find current max index of the category
 * @param $categories - array of current categories
 * @return int - max index of the category or -1
 */
function findCategoryMaxIndex($categories): int{
        if (empty($categories)){
            return -1;
        }

        $cur_maxindex = -10;
        foreach ($categories as $category){
            if($category["id"] > $cur_maxindex){
                $cur_maxindex = $category["id"];
            }
        }
        return $cur_maxindex;
    }

