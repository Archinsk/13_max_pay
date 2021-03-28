//Валидация выбора пользователя при очистке истории
clearHistoryForm.onsubmit = function () {
    if  (clearHistorySelectHeader.selected == true) {
        clear_history_user.classList.add("error");
        return false;
    } else {
        clearHistoryForm.submit();
    }
};

clearHistoryForm.onchange = function () {
    if (clear_history_user.classList.contains("error")) {
        clear_history_user.classList.remove("error");
    }
}