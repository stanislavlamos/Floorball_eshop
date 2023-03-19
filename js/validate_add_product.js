/**
 * Function to validate add product form after submit
 * @returns {boolean}
 */
function validate_add_product(){
    if (document.getElementsByClassName("problem_cond").length > 0){
        document.getElementsByClassName("problem_cond")[0].remove();
    }

    document.getElementById("product_name").style.border = "solid green";
    document.getElementById("product_picture").style.border = "solid green";
    document.getElementById("category_list").style.border = "solid green";
    document.getElementById("color_picker_1").style.border = "solid green";
    document.getElementById("color_picker_2").style.border = "solid green";
    document.getElementById("color_picker_3").style.border = "solid green";
    document.getElementById("product_price").style.border = "solid green";
    document.getElementById("product_text").style.border = "solid green";

    const product_name = document.getElementById("product_name").value.trim().replace(/['"]+/g, '');
    const product_picture = document.getElementById("product_picture");
    const product_price = document.getElementById("product_price").value;
    const product_text = document.getElementById("product_text").value.trim().replace(/['"]+/g, '');

    const problems_arr = [];
    let name_problem = false;
    let price_problem = false;
    let text_problem = false;
    let file_problem = false;

    if (product_name.length < 10 || product_name.length > 50){
        problems_arr.push("Nesprávná délka jména produktu");
        name_problem = true;
    }

    if (parseInt(product_price) < 100 || parseInt(product_price) > 100000){
        problems_arr.push("Nevalidní hodnota ceny");
        price_problem = true;
    }

    if (product_text.length < 20 || product_text.length > 300){
        problems_arr.push("Nesprávná délka textu produktu");
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

    document.getElementsByClassName("form_requirements_reg")[0].innerHTML += new_html;

    if (name_problem){
        document.getElementById("product_name").style.border = "solid red";
    }

    if (price_problem){
        document.getElementById("product_price").style.border = "solid red";
    }

    if (text_problem){
        document.getElementById("product_text").style.border = "solid red";
    }

    return false;
}

window.onload = function () {
    const product_name = document.getElementById("product_name");
    const product_picture = document.getElementById("product_picture");
    const category_list = document.getElementById("category_list");
    const color_picker_1 = document.getElementById("color_picker_1");
    const color_picker_2 = document.getElementById("color_picker_2");
    const color_picker_3 = document.getElementById("color_picker_3");
    const product_price = document.getElementById("product_price");
    const product_text = document.getElementById("product_text");

    product_name.addEventListener("keyup", product_name_listener);
    product_picture.addEventListener("click", picture_listener);
    category_list.addEventListener("click", category_listener);
    color_picker_1.addEventListener("click", color_listener_one);
    color_picker_2.addEventListener("click", color_listener_two);
    color_picker_3.addEventListener("click", color_listener_three)
    product_price.addEventListener("keyup", price_listener);
    product_text.addEventListener("keyup", text_listener);
}

/**
 * Function to check product name during typing
 */
function product_name_listener(){
    const product_name = document.getElementById("product_name").value.trim().replace(/['"]+/g, '');

    if (product_name.length < 10 || product_name.length > 50){
        document.getElementById("product_name").style.border = "solid red";
    }else {
        document.getElementById("product_name").style.border = "solid green";
    }
}

/**
 * Function to check picture file during uploading
 */
function picture_listener(){
    document.getElementById("product_picture").style.border = "solid green";
}

/**
 * Function to check category during selection
 */
function category_listener(){
    document.getElementById("category_list").style.border = "solid green";
}

/**
 * Function to check color during selection
 */
function color_listener_one(){
    document.getElementById("color_picker_1").style.border = "solid green";
}

/**
 * Function to check color during selection
 */
function color_listener_two(){
    document.getElementById("color_picker_2").style.border = "solid green";
}

/**
 * Function to check color during selection
 */
function color_listener_three(){
    document.getElementById("color_picker_3").style.border = "solid green";
}

/**
 * Function to check price input during typing
 */
function price_listener(){
    const product_price = document.getElementById("product_price").value;

    if (parseInt(product_price) < 100 || parseInt(product_price) > 100000){
        document.getElementById("product_price").style.border = "solid red";
    }else {
        document.getElementById("product_price").style.border = "solid green";
    }
}

/**
 * Function to check product text during typing
 */
function text_listener(){
    const product_text = document.getElementById("product_text").value.trim().replace(/['"]+/g, '');

    if (product_text.length < 20 || product_text.length > 300){
        document.getElementById("product_text").style.border = "solid red";
    } else {
        document.getElementById("product_text").style.border = "solid green";
    }
}