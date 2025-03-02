document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const errorMessage = document.querySelector("#error-message");

    form.addEventListener("submit", function (e) {
        errorMessage.innerHTML = "";
        let valid = validateForm();

        if (!valid) {
            e.preventDefault();
        }
    });
});


function validateForm() {
    let isValid = true;

    if (!validateEmptyFields()) {
        isValid = false;
    }

    if (!validateEmail()) {
        isValid = false;
    }

    if (!validatePassword()) {
        isValid = false;
    }

    return isValid;
}


function validateEmail() {
    const email = document.querySelector("#email");
    if (!email.value.includes("@") || !email.value.includes(".")) {
        displayError("Please enter a valid email address.");
        return false;
    }
    return true;
}



function validatePassword() {
    const password = document.querySelector("#password");
    if (password.value.length < 6) {
        displayError("Password must be at least 6 characters long.");
        return false;
    }
    return true;
}


function displayError(message) {
    const errorMessage = document.querySelector("#error-message");
    errorMessage.innerHTML += `<p>${message}</p>`;
}
function validateEmptyFields() {
    let isValid = true;
    const fields = document.querySelectorAll("input[required]");

    fields.forEach(field => {
        if (field.value.trim() === "") {
            displayError(`${field.previousElementSibling.textContent} is required.`);
            isValid = false;
        }
    });
    return isValid;
}
