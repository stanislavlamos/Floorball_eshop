/**
 * Function to validate discussion forms during submission
 * @returns {boolean}
 */
function validate_discussion(){
    if (document.getElementsByClassName("problem_cond").length > 0){
        document.getElementsByClassName("problem_cond")[0].remove();
    }

    document.getElementById("comment_header").style.border = "solid green";
    document.getElementById("comment_text").style.border = "solid green";

    const comment_header = document.getElementById("comment_header").value.trim().replace(/['"]+/g, '');
    const comment_text = document.getElementById("comment_text").value.trim().replace(/['"]+/g, '');

    const problems_arr = [];
    let header_problem = false;
    let text_problem = false;

    if (comment_header.length < 10 || comment_header > 50){
        problems_arr.push("Délka nadpisu není správně");
        header_problem = true;
    }

    if (comment_text.length < 20 || comment_text.length > 300){
        problems_arr.push("Délka textu není správně");
        text_problem = true;
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

    if (text_problem){
        document.getElementById("comment_text").style.border = "solid red";
    }

    if (header_problem){
        document.getElementById("comment_header").style.border = "solid red";
    }

    return false;
}

window.ondevicemotion = function (){
    const comment_header = document.getElementById("comment_header");
    const comment_text = document.getElementById("comment_text");

    comment_header.addEventListener("keyup", header_listener);
    comment_text.addEventListener("keyup", comment_text_listener);
}

/**
 * Function to validate comment header during typing
 */
function header_listener(){
    const comment_header = document.getElementById("comment_header").value.trim().replace(/['"]+/g, '');

    if (comment_header.length < 10 || comment_header.length > 50){
        document.getElementById("comment_header").style.border = "solid red";
    } else {
        document.getElementById("comment_header").style.border = "solid green";
    }
}

/**
 * Function to validate comment text during typing
 */
function comment_text_listener(){
    const comment_text = document.getElementById("comment_text").value.trim().replace(/['"]+/g, '');

    if (comment_text.length < 20 || comment_text.length > 300){
        document.getElementById("comment_text").style.border = "solid red";
    } else {
        document.getElementById("comment_text").style.border = "solid green";
    }
}
