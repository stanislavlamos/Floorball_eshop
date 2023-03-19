<?php
/**
 * Load comments from JSON file
 * @return array - array of comments stored in JSON file
 */
function loadComments(): array {
        if (!file_exists("../db_files/comments.json")) {
            file_put_contents("../db_files/comments.json", '[]');
        }
        return json_decode(file_get_contents("../db_files/comments.json"), true);
    }

/**
 * Function to find comment based on the given id
 * @param $id - id of the desired comment
 * @param $comments - array of comments stored in JSON file
 * @return array|null - found comment based on given id
 */
function findCommentById($id, $comments): ?array {
        if (empty($comments)){
            return null;
        }

        foreach($comments as $comment) {
            if ($comment["id"] == $id) {
                return $comment;
            }
        }
        return null;
    }


/**
 * Function to load all comments except the one with the given id
 * @param $id - id of comment to left out
 * @return array - array of other retrieved comments
 */
function loadCommentsExceptId($id): array
    {
        $comments = loadComments();
        $filtered_comments = array();

        foreach ($comments as $unfiltered_comment){
            if ($unfiltered_comment["id"] != $id){
                $filtered_comments[] = $unfiltered_comment;
            }
        }
        return $filtered_comments;
    }

/**
 * Function to delete all comments from product with given id
 * @param $product_id - id of the product
 * @param $comments - comments stored in JSON file
 * @return void
 */
function deleteCommentsByProductId($product_id, $comments){
        if (empty($comments)){
            return;
        }

        $final_comments = array();
        foreach($comments as $comment) {
            if ($comment["product_id"] != $product_id) {
                $final_comments[] = $comment;
            }
        }

        saveComments($final_comments);
    }

/**
 * Function to find all comments with corresponding product id
 * @param $product_id - id of the desired product
 * @param $comments - all comments stored in JSON file
 * @return array - array of all comments from product with given id
 */
function findCommentByProductId($product_id, $comments): array {
        if (empty($comments)){
            return array();
        }

        $final_comments = array();
        foreach($comments as $comment) {
            if ($comment["product_id"] == $product_id) {
                $final_comments[] = $comment;
            }
        }
        return $final_comments;
    }

/**
 * Function to find max index from all comments
 * @param $comments - all comments stored in JSON file
 * @return int - found max index or -1
 */
function findCommentMaxIndex($comments): int {
        if (empty($comments)){
            return -1;
        }

        $cur_maxindex = -10;
        foreach ($comments as $comment){
            if($comment["id"] > $cur_maxindex){
                $cur_maxindex = $comment["id"];
            }
        }
        return $cur_maxindex;
    }


/**
 * Function to save comments to JSON file
 * @param $comments - comments to save
 * @return void
 */
function saveComments($comments) {
        $fileContent = json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents("../db_files/comments.json", $fileContent);
    }

/**
 * Function to clear string input from comments forms
 * @param $data - string to clear
 * @return string - cleared string
 */
function clear_comment_string_input($data): string {
        $data = stripslashes(trim($data));

        return $data;
    }

