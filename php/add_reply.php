<?php
include_once "comment_utils.php";

session_start();

if (isset($_SESSION['sort-table'])){
    unset($_SESSION['sort-table']);
}

if (isset($_SESSION['category-table'])){
    unset($_SESSION['category-table']);
}

$problems_arr = array();
$comment_problem = false;
$header_problem = false;

if (!(isset($_SESSION['user'])) or !$_SESSION['user']['isAdmin']){
    header('Location: product_gallery.php');
    die();
}

if (!$_SESSION['user']['isAdmin'] and !$_SESSION['user']['isApproved']){
    header('Location: logout.php');
    die();
}

if (isset($_GET['reply_comment_id'])) {
    $_SESSION["reply_comment_id"] = intval($_GET['reply_comment_id']);
}

if (isset($_POST['comment_header'], $_POST['comment_text'], $_SESSION["visiting_product_id"], $_SESSION['reply_comment_id'])){
    $comment_header = clear_comment_string_input($_POST['comment_header']);
    $comment_text = clear_comment_string_input($_POST['comment_text']);
    $product_id = $_SESSION['visiting_product_id'];
    $comment_id = $_SESSION['reply_comment_id'];

    if (strlen($comment_header) < 10 or strlen($comment_header) > 50){
        $problems_arr[] = "Délka nadpisu není správně";
        $header_problem = true;
    }

    if (strlen($comment_text) < 20 or strlen($comment_text) > 300){
        $problems_arr[] = "Délka textu není správně";
        $comment_problem = true;
    }

    if (count($problems_arr) == 0){
        $all_comments = loadComments();
        $edit_comment = findCommentById($comment_id, $all_comments);

        $edit_comment['reply_header'] = $comment_header;
        $edit_comment['reply_text'] = $comment_text;

        $all_comments = loadCommentsExceptId($comment_id);

        if (empty($all_comments)){
            $all_comments = array();
        }
        array_push($all_comments, $edit_comment);
        saveComments($all_comments);
        header("Location: product.php?desired_product_id=" . $_SESSION['visiting_product_id']);
        die();
    }
}
?>


<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <title>Florbal eshop</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/validate_discussion.js"></script>
    <meta charset="utf-8">
</head>
<body>
<header>
    <div class="main_nav">
        <?php include_once "navigation.php"?>
    </div>
</header>

<main id="log_main">
    <div class="add-comment-table">
        <form class="add-comment-reply" action="add_reply.php" method="post" onsubmit="return validate_discussion();">
            <h1 class="add_comment_title">Přidání odpovědi</h1>

            <div class="comment_label"><label for="comment_header">Nadpis*</label><br></div>
            <div class="comment_field"><input type="text" class="<?php if ($header_problem){echo "comment_header_red";}?>" id="comment_header" name="comment_header" required value="<?php if(isset($_POST['comment_header'])){ echo htmlspecialchars($_POST['comment_header']);}?>"><br></div>

            <div class="comment_label"><label for="comment_text">Text*</label><br></div>
            <div class="comment_field_text"><textarea class="<?php if ($comment_problem){echo "comment_text_red";}?>" id="comment_text" name="comment_text" rows="3" cols="50" required><?php if(isset($_POST['comment_text'])){ echo htmlspecialchars($_POST['comment_text']);}?></textarea><br></div>

            <label for="cm_submit"></label>
            <div class="comment_submit"><input type="submit" id="cm_submit" name="cm_submit" value="Odeslat odpověď"></div>
        </form>
    </div>
    <div class="form_requirements_log">
        <div class="log_cond">
            <h3 class="form_requirements_title">Požadavky formuláře</h3>
            <ul>
                <li>Pole označená hvězdičkou jsou povinná</li>
                <li>Text příspěvku musí mít 20 - 300 znaků</li>
                <li>Nadpis musí mít 10 - 50 znaků</li>
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