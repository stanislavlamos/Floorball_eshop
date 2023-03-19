const email_reg = /^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$/g;

/**
 * Function to validate login during typing
 * @returns {boolean}
 */
function validate_login(){
    if (document.getElementsByClassName("problem_cond").length > 0){
        document.getElementsByClassName("problem_cond")[0].remove();
    }

    document.getElementById("email_addr_signin").style.border = "solid green";
    document.getElementById("password_sign").style.border = "solid green";

    const email_addr = document.getElementById("email_addr_signin").value.trim().replace(/['"]+/g, '');
    const password_first = document.getElementById("password_sign").value.trim().replace(/['"]+/g, '');

    const problems_arr = [];
    let password_mismatch = false;
    let email_problem = false;

    if (email_addr.length === 0 || email_addr.length > 100 || !email_addr.match(email_reg)){
        problems_arr.push("Email není validní");
        email_problem = true;
    }

    if (password_first.length < 10){
        problems_arr.push("Heslo je příliš krátké");
        password_mismatch = true;
    }

    if (password_first.length > 20){
        problems_arr.push("Heslo je příliš dlouhé");
        password_mismatch = true;
    }

    if (!password_first.match(/[0-9]/)){
        problems_arr.push("Heslo neobsahuje číslici");
        password_mismatch = true;
    }

    if (!password_first.match(/[A-Z]/)){
        problems_arr.push("Heslo neobsahuje velké písmeno");
        password_mismatch = true;
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
    document.getElementById("password_sign").value = "";

    if (password_mismatch){
        document.getElementById("password_sign").style.border = "solid red";
    }

    if (email_problem){
        document.getElementById("email_addr_signin").style.border = "solid red";
    }

    return false;
}

window.onload = function () {
    const email_addr = document.getElementById("email_addr_signin");
    const password_first = document.getElementById("password_sign");

    email_addr.addEventListener("keyup", email_addr_listener_sign);
    password_first.addEventListener("keyup", password_first_listener_sign);
}

/**
 * Function to validate email address during typing
 */
function email_addr_listener_sign(){
    const email_addr = document.getElementById("email_addr_signin").value.trim().replace(/['"]+/g, '');

    if (email_addr.length === 0 || email_addr.length > 100 || !email_addr.match(email_reg)){
        document.getElementById("email_addr_signin").style.border = "solid red";
    }else {
        document.getElementById("email_addr_signin").style.border = "solid green";
    }
}

/**
 * Function to validate password during typing
 */
function password_first_listener_sign(){
    const password_first = document.getElementById("password_sign").value.trim().replace(/['"]+/g, '');

    if (!password_first.match(/[0-9]/) || !password_first.match(/[A-Z]/) || password_first.length < 10 || password_first.length > 20){
        document.getElementById("password_sign").style.border = "solid red";
    } else {
        document.getElementById("password_sign").style.border = "solid green";
    }
}