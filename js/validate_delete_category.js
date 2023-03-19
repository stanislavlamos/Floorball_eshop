/**
 * Show hint during typing category name
 * @param str
 */
function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "get_hint.php?q=" + str, true);
        xmlhttp.send();
    }
}

/**
 * Function to check delete category form after submit
 * @returns {boolean}
 */
function validate_delete_category(){
    if (document.getElementsByClassName("problem_cond").length > 0){
        document.getElementsByClassName("problem_cond")[0].remove();
    }

    document.getElementById("category_name").style.border = "solid green";
    const category_text = document.getElementById("category_name").value.trim().replace(/['"]+/g, '');


    const problems_arr = [];
    let category_text_problem = false;

    if (category_text.length < 5 || category_text.length > 50){
        problems_arr.push("Délka názvu kategorie není správná");
        category_text_problem = true;
    }

    if (problems_arr.length === 0){
        return true;
    }

    let new_html = "";
    new_html += "" +
        "<div class=\"problem_cond\">" +
        "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>" +
        "<ul>";

    let i = 0;
    while (i < problems_arr.length){
        let cur_problem = problems_arr[i];

        new_html += "" +
            "<li>" + cur_problem + "</li>";

        i += 1;
    }

    new_html += "" +
        "</ul>" +
        "</div>";

    document.getElementsByClassName("form_requirements_log")[0].innerHTML += new_html;

    if (category_text_problem){
        document.getElementById("category_name").style.border = "solid red";
    }

    return false;
}

window.onload = function () {
    const category_text = document.getElementById("category_name");

    category_text.addEventListener("keyup", category_text_listener);
}

/**
 * Function to check category name during typing
 */
function category_text_listener(){
    const category_text = document.getElementById("category_name").value.trim().replace(/['"]+/g, '');

    if (category_text.length < 5 || category_text.length > 50){
        document.getElementById("category_name").style.border = "solid red";
    } else {
        document.getElementById("category_name").style.border = "solid green";
    }
}