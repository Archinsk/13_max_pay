signupLogin.onblur = function () {
    if ((signupLogin.value).trim() == "") { // если поле не заполнено или заполнено пробелами
        signupLogin.classList.add("error");
        signupLogin.value = "";
        signupLoginLabel.firstChild.textContent = "Введите логин";
    }
};

signupLogin.oninput = function () {
    signupLogin.classList.remove("error");
    if (logSign.children.length > 2) {
        signupLogin.classList.remove("error");
        logSign.lastElementChild.remove();
    }
    signupLogin.setAttribute("value", signupLogin.value);
    signupLoginLabel.firstChild.textContent = "Логин";
};

signupPassword.onblur = function () {
    if ((signupPassword.value).trim() == "") { // если поле не заполнено или заполнено пробелами
        signupPassword.classList.add("error");
        signupPassword.value = "";
        signupPasswordLabel.firstChild.textContent = "Введите пароль";
    }
};

signupPassword.oninput = function () {
    signupPassword.classList.remove("error");
    signupPassword.setAttribute("value", signupPassword.value);
    signupPasswordLabel.firstChild.textContent = "Пароль";
    if ((signupPassword.value == verifySignupPassword.value) && (passVerify.children.length > 2)) {
        verifySignupPassword.classList.remove("error");
        verifyPasswordError.remove();
    }
};

verifySignupPassword.onblur = function () {
    if ((verifySignupPassword.value).trim() == "") { // если поле не заполнено или заполнено пробелами
        verifySignupPassword.classList.add("error");
        verifySignupPassword.value = "";
        verifySignupPasswordLabel.firstChild.textContent = 'Повторите пароль';
    } else if (signupPassword.value != verifySignupPassword.value) {
        verifySignupPassword.classList.add("error");
        if (passVerify.children.length == 2) {
            let errorComment = createComment();
            let commentContent = document.createTextNode("Повтор не совпадает с паролем");
            errorComment.appendChild(commentContent);
            passVerify.appendChild(errorComment);
        }
    }
};

verifySignupPassword.oninput = function () {
    verifySignupPassword.classList.remove("error");
    verifySignupPassword.setAttribute("value", verifySignupPassword.value);
    verifySignupPasswordLabel.firstChild.textContent = "Пароль ещё раз";
    if (signupPassword.value == verifySignupPassword.value) {
        verifyPasswordError.remove();
    }
};

function createComment() {
    let comment = document.createElement("div");
    comment.setAttribute("id", "class");
    comment.id = "verifyPasswordError";
    comment.className = "form-text";
    let textValue = document.createTextNode("");
    comment.appendChild(textValue);
    return comment;
}

registrationForm.onsubmit = function () {
    if  (!signupLogin.value.trim()) {
        signupLogin.classList.add("error");
        signupLoginLabel.firstChild.textContent = "Введите логин";
        return false;
    }
    if  (!signupPassword.value.trim()) {
        signupPassword.classList.add("error");
        signupPasswordLabel.firstChild.textContent = "Введите пароль";
        return false;
    }
    if  (!verifySignupPassword.value.trim()) {
        verifySignupPassword.classList.add("error");
        verifySignupPasswordLabel.firstChild.textContent = 'Повторите пароль';
        return false;
    }
    //Проверка на наличие ошибок
    if (signupLogin.classList.contains("error") || signupPassword.classList.contains("error") || verifySignupPassword.classList.contains("error")) {
        return false;
    } else {
        registrationForm.submit();
    }
}