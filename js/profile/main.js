createTextbox();
function createTextbox(){
    let infoFields = document.getElementsByTagName("dd");
    let textName = ["email", "phone", "address", "city", "country", "gender", "dob"];
    for (let i = 0; i < infoFields.length-1; i++) {
        let editTag = document.createElement('input');
        editTag.value = infoFields[i].innerHTML;
        editTag.name = textName[i];
        editTag.id = textName[i];
        editTag.className = "txtEditOnPage";
        editTag.style.display = "none";

        var parent = infoFields[i].parentNode;
        parent.insertBefore(editTag, parent.childNodes[2]);
    }
}

function editOnPage(){
    document.getElementById("btnEdit").style.display = "none";
    document.getElementById("btnSubmit").style.display = "inline";
    let infoFields = document.getElementsByClassName("txtEditOnPage");
    let viewFields = document.getElementsByTagName("dd");
    for (let i = 0; i < infoFields.length; i++) {
        infoFields[i].style.display = "block";
        viewFields[i].style.display = "none";
    }
}

let checkEntry = {
    "email":[true, 'errEmail', 'Email must NOT be empty and follow format abc@domain'],
    "phone":[true, 'errPhone', 'Phone number must NOT be empty and should be only 10-11 digits'],
};

function validateEdit(){
    let check = true;
    let keys = Object.keys(checkEntry);
    // console.log(checkEntry);
    for (let i = 0; i < keys.length; i++) {
        if (checkEntry[keys[i]][0] == false){
            check = false;
            break;
        }
    }

    if (check)
        document.getElementById("btnSubmit").disabled = false;
    else
        document.getElementById("btnSubmit").disabled = true;
}

function updateValidateVisual(key, regex){
    let element = document.getElementById(key);
    let isValid = regex.test(element.value);
    if (isValid) {
        element.style.border = "4px solid #008000";
        document.getElementById(checkEntry[key][1]).innerHTML = "";
        checkEntry[key][0] = true;
    } else {
        element.style.border = "4px solid #FF0000";
        document.getElementById(checkEntry[key][1]).innerHTML = checkEntry[key][2];
        checkEntry[key][0] = false;
    }
    validateEdit();
}

$( document ).ready(function() {
    console.log("Ready!");
    $("#email").keyup(function (){
        updateValidateVisual("email", /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
    });
    $("#phone").keyup(function (){
        updateValidateVisual("phone", /^[0-9]{10,11}$/);
    });
});