<?php
include_once "category_utils.php";

$a = getAllCategoryNames();
$q = $_REQUEST["q"];

$hint = "";

if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    foreach($a as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if ($hint === "") {
                $hint = htmlspecialchars($name);
            } else {
                $hint .= htmlspecialchars(", $name");
            }
        }
    }
}

echo $hint === "" ? "Žádné návrhy" : $hint;
?>
