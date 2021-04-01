//Скрипты валидации формы регистрации

signupLogin.onblur = function () {
    emptyFieldError(this, "Введите логин");
};

signupLogin.oninput = function () {
    removeError(this, "Логин");
};

signupPassword.onblur = function () {
    emptyFieldError(this, "Введите пароль");
};

signupPassword.oninput = function () {
    signupPassword.classList.remove("error");
    signupPasswordLabel.firstChild.textContent = "Пароль";
    if ((signupPassword.value == verifySignupPassword.value) && (passVerify.children.length > 2)) {
        verifySignupPassword.classList.remove("error");
        passVerify.lastElementChild.remove();
    } else if ( (signupPassword.value != verifySignupPassword.value) && verifySignupPassword.value.trim()) {
        verifySignupPassword.classList.add("error");
        if (passVerify.children.length == 2) {
            passVerify.appendChild(createErrorComment("Повтор не совпадает с паролем"));
        }
    }
};

verifySignupPassword.onblur = function () {
    if (!emptyFieldError(this, "Повторите логин") && (signupPassword.value != verifySignupPassword.value) ) {
        verifySignupPassword.classList.add("error");
        if (passVerify.children.length == 2) {
            passVerify.appendChild(createErrorComment("Повтор не совпадает с паролем"));
        }
    }
};

verifySignupPassword.oninput = function () {
    verifySignupPassword.classList.remove("error");
    verifySignupPasswordLabel.firstChild.textContent = "Пароль ещё раз";
    if ((signupPassword.value == verifySignupPassword.value) && (passVerify.children.length > 2)) {
        verifySignupPassword.classList.remove("error");
        passVerify.lastElementChild.remove();
    }
};

registrationForm.onsubmit = function () {
    if  (emptyFieldError(signupLogin, "Введите логин") || emptyFieldError(signupPassword, "Введите пароль") || emptyFieldError(verifySignupPassword, "Повторите пароль")) {
        return false;
    }
    //Проверка на наличие ошибок
    if (signupLogin.classList.contains("error") || signupPassword.classList.contains("error") || verifySignupPassword.classList.contains("error")) {
        return false;
    } else {
        registrationForm.submit();
    }
};

//Скрипты валидации формы авторизации

inputLogin.onblur = function () {
    emptyFieldError(this, "Введите логин");
};

inputLogin.oninput = function () {
    removeError(this, "Логин");
};

inputPassword.onblur = function () {
    emptyFieldError(this, "Введите пароль");
};

inputPassword.oninput = function () {
    removeError(this, "Пароль");
};

authorizationForm.onsubmit = function () {
    if (emptyFieldError(inputLogin, "Введите логин") || emptyFieldError(inputPassword, "Введите пароль")) {
        return false;
    }
    //Проверка на наличие ошибок
    if (inputLogin.classList.contains("error") || inputPassword.classList.contains("error")) {
        return false;
    } else {
        authorizationForm.submit();
    }
};

function createErrorComment(errorComment) {
    let comment = document.createElement("div");
    comment.className = "form-text";
    let textValue = document.createTextNode(errorComment);
    comment.appendChild(textValue);
    return comment;
}

function emptyFieldError(field, label){
    if (!field.value.trim()) { // если поле не заполнено или заполнено пробелами
        field.classList.add("error");
        field.value = "";
        field.nextElementSibling.firstChild.textContent = label;
        return true;
    }
    return false;
}

function removeError(field, label){
    field.classList.remove("error");
    if (field.parentElement.children.length > 2) {
        field.parentElement.lastElementChild.remove();
    }
    field.nextElementSibling.firstChild.textContent = label;
}
