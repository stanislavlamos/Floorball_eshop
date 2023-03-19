const email_reg = /^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$/g;

/**
 * Function to validate registration form after submit
 * @returns {boolean}
 */
function validate_registration(){
    if (document.getElementsByClassName("problem_cond").length > 0){
        document.getElementsByClassName("problem_cond")[0].remove();
    }

    document.getElementById("password_first").style.border = "solid green";
    document.getElementById("password_second").style.border = "solid green";
    document.getElementById("email_addr").style.border = "solid green";
    document.getElementById("first_name").style.border = "solid green";
    document.getElementById("last_name").style.border = "solid green";
    document.getElementById("birth_date").style.border = "solid green";

    const first_name = document.getElementById("first_name").value.trim().replace(/['"]+/g, '');
    const last_name = document.getElementById("last_name").value.trim().replace(/['"]+/g, '');
    const email_addr = document.getElementById("email_addr").value.trim().replace(/['"]+/g, '');
    const password_first = document.getElementById("password_first").value.trim().replace(/['"]+/g, '');
    const password_second = document.getElementById("password_second").value.trim().replace(/['"]+/g, '');

    const problems_arr = [];
    let first_name_problem = false;
    let last_name_problem = false;
    let password_mismatch = false;
    let email_problem = false;

    if (email_addr.length === 0 || email_addr.length > 100 || !email_addr.match(email_reg)){
        problems_arr.push("Email není validní");
        email_problem = true;
    }

    if (first_name.length > 20){
        problems_arr.push("Jméno má nesprávný formát");
        first_name_problem = true;
    }

    if (last_name.length > 20){
        problems_arr.push("Příjmení má nesprávný formát");
        last_name_problem = true;
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

    if (password_first !== password_second){
        problems_arr.push("Hesla se neshodují");
        password_mismatch = true;
    }

    if (problems_arr.length === 0){
        return true;
    }

    let new_html = "";
    new_html += "" +
        "<div class=\"problem_cond\">" +
        "<h3 class=\"form_requirements_title\">Problémy při odesílání formuláře</h3>" +
        "<p>" +
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
        "</p>" +
        "</div>";

    document.getElementsByClassName("form_requirements_reg")[0].innerHTML += new_html;
    document.getElementById("password_first").value = "";
    document.getElementById("password_second").value = "";

    if (password_mismatch){
        document.getElementById("password_first").style.border = "solid red";
        document.getElementById("password_second").style.border = "solid red";
    }

    if (email_problem){
        document.getElementById("email_addr").style.border = "solid red";
    }

    if (first_name_problem){
        document.getElementById("first_name").style.border = "solid red";
    }

    if (last_name_problem){
        document.getElementById("last_name").style.border = "solid red";
    }

    return false;
}

window.onload = function () {
    const first_name = document.getElementById("first_name");
    const last_name = document.getElementById("last_name");
    const email_addr = document.getElementById("email_addr");
    const password_first = document.getElementById("password_first");
    const password_second = document.getElementById("password_second");

    first_name.addEventListener("keyup", first_name_listener);
    last_name.addEventListener("keyup", last_name_listener);
    email_addr.addEventListener("keyup", email_addr_listener);
    password_first.addEventListener("keyup", password_first_listener);
    password_second.addEventListener("keyup", password_second_listener);
}

/**
 * Function to validate first name during typing
 */
function first_name_listener(){
    const first_name = document.getElementById("first_name").value.trim().replace(/['"]+/g, '');

    if (first_name.length > 20) {
        document.getElementById("first_name").style.border = "solid red";
    }

    else if (first_name.length < 20){
        document.getElementById("first_name").style.border = "solid green";
    }
}

/**
 * Function to validate last name during typing
 */
function last_name_listener(){
    const last_name = document.getElementById("last_name").value.trim().replace(/['"]+/g, '');

    if (last_name.length > 20) {
        document.getElementById("last_name").style.border = "solid red";
    }

    else if (last_name.length < 20){
        document.getElementById("last_name").style.border = "solid green";
    }
}

/**
 * Function to validate email address during typing
 */
function email_addr_listener(){
    const email_addr = document.getElementById("email_addr").value.trim().replace(/['"]+/g, '');

    if (email_addr.length === 0 || email_addr.length > 100 || !email_addr.match(email_reg)){
        document.getElementById("email_addr").style.border = "solid red";
    }else {
        document.getElementById("email_addr").style.border = "solid green";
    }
}

/**
 * Function to validate password during typing
 */
function password_first_listener(){
    const password_first = document.getElementById("password_first").value.trim().replace(/['"]+/g, '');

    if (!password_first.match(/[0-9]/) || !password_first.match(/[A-Z]/) || password_first.length < 10 || password_first.length > 20){
        document.getElementById("password_first").style.border = "solid red";
    } else {
        document.getElementById("password_first").style.border = "solid green";
    }
}

/**
 * Function to validate second password during typing
 */
function password_second_listener(){
    const password_second = document.getElementById("password_second").value.trim().replace(/['"]+/g, '');

    if (!password_second.match(/[0-9]/) || !password_second.match(/[A-Z]/) || password_second.length < 10 || password_second.length > 20){
        document.getElementById("password_second").style.border = "solid red";
    } else {
        document.getElementById("password_second").style.border = "solid green";
    }
}