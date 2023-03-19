<?php
    include_once "product_utils.php";
    include_once "comment_utils.php";
    include_once "utils.php";

    session_start();

    if (isset($_GET['delete_product_submit']) and isset($_SESSION['user']) and $_SESSION['user']['isAdmin'] and isset($_SESSION['visiting_product_id'])){
        $products = loadProductsExceptId($_SESSION['visiting_product_id']);
        deleteCommentsByProductId($_SESSION['visiting_product_id'], loadComments());
        deleteProductsFromWishList($_SESSION['visiting_product_id']);
        unlink(findProductById($_SESSION['visiting_product_id'], loadProducts())['product_picture_path']);
        $_SESSION['product_deleted'] = 1;
        saveProducts($products);
        header('Location: delete_product_success.php');
        die();
    }else{
        header('Location: product_gallery.php');
        die();
    }
?>
