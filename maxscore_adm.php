<?php //Страница авторизованного админа

// Подключаем RedBeanPHP
require 'rb.php';
// Соединямся с БД
R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' ); //Указываем адрес сервера, имя базы, логин и пароль пользователя (синтаксис RedBeanPHP)
// Открываем сессию
session_start();

//Если на этой странице оказался незарегистрированный или не админ, то переводим его на соответствующую страницу
if(!$_SESSION) {
  header('Location: maxscore.php');
} else if ($_SESSION['is_admin'] == false) {
  header('Location: maxscore_auth.php');
}

// В переменную data кладем все что передается от формы
$data = $_POST;
if(isset($data['do_login'])) { //Если нажата кнопка Войти.
//Создаем массив ошибок
$errors = array();
//Ищем пользователя по логину
$user = R::findOne('users', 'login = ?', array($data['userLogin']));
//Если логин существует,
if ( $user ) {
//то проверяем пароль
if ( password_verify($data['userPassword'], $user->password) )
{
//если пароль совпадает, то нужно авторизовать пользователя
$userarray=$user->export();
$_SESSION['logged_user'] = $userarray['login'];
echo '<div style="color:dreen;">' .$_SESSION['logged_user']. ', вы авторизованы!<hr>';
}else
{
$errors[] = 'Неверно введен пароль!';
}
} else { //иначе (пользователь не найден)
$errors[] = 'Пользователь с таким логином не найден!';
}
//Вывод ошибок
if ( ! empty($errors) ) { //Если массив с ошибками не пуст
echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>'; // то выводим ошибки авторизации
}
}
?>

<!DOCTYPE html> <!-- Документ HTML5 -->
<html lang='ru'>

<head>
    <!-- Установка кодировки -->
    <meta charset='utf-8'>
    <!-- Для правильного масштабирования и установки ширины страницы на мобильных устройствах -->
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- Цвет вкладки в мобильном Chrome -->
    <meta name="theme-color" content="#175a7e">
    <!-- Иконка страницы -->
    <link rel="icon" href="favicon.gif" type="image/gif">
    <!-- Стили Bootstrap 5.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- Шрифт иконок Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <!-- Стили maxscore -->
    <link rel="stylesheet" href="maxPayStyle.css">
    <title>Max Score</title> <!-- Название вкладки в браузере -->
</head>

<body>

<div class="container pt-3">
  <header class="header">
    <div class="alert text-white text-center" role="alert">
        <h2>Max Pay</h2>
        <a href="logout.php" class="btn btn-warning ms-3"><i class="bi bi-box-arrow-right"></i></a>
        <button type="button" class="btn btn-outline-warning ms-3" data-bs-toggle="modal" data-bs-target="#clearHistoryModal"><i class="bi bi-trash"></i></button>
    </div>
  </header>
</div>

<main>
    <div id="stick" class="container">
        <div class="alert text-white text-center mb-2" role="alert">
          <p>Вы - администратор системы Max Pay</p>
        </div>
    </div>

<?php
  //Ищем записи пользователей, не являющихся админом
  $userslist = R::find( 'users', 'isAdmin = :admin', [':admin' => false] );

  //Выводим записи всех, не являющихся админом пользователей
  foreach( $userslist as $anyuser) {
    $purchaseslist = R::find( 'purchases', 'login = :name', [':name' => $anyuser->login] );
    echo '
        <form style="position: relative;" action="addcash.php" method="POST">
          <div class="container">
            <div class="row pt-2" ondrop="drop(event)" ondragover="allowDrop(event)">
              <div class="col-md-6">
                <details class="mb-2">
                  <summary>' .$anyuser->login. '</summary>
                  <ul>';
                    foreach( $purchaseslist as $anypurchase) {
                      //Вычисляем дату транзакции
                      $purchasesdate = getdate($anypurchase->purchase_date + 60*60*4)['mday'] . '.' . sprintf("%02d", getdate($anypurchase->purchase_date + 60*60*4)["mon"]) . '.' . getdate($anypurchase->purchase_date + 60*60*4)['year'];
                      //Формируем текст записи списка
                      $purchaseItem = $anypurchase->export();
                      $payer = $purchaseItem['payer_admin'];
                      if (isset($payer)) {
                        echo '
                            <li>Плюс ' .$purchasesdate. ' - <span class="badge refill">' .$anypurchase->cost. ' руб</span> от ' .$anypurchase->payer_admin. '</li>';
                      } else {
                        echo '
                            <li>Покупка - ' .$purchasesdate. ' - <span class="badge">' .$anypurchase->cost. ' руб</span>' .$message. '</li>';
                      }
                    }
                  echo '
                  </ul>
                </details>
              </div>
              <div class="col-md-1 visually-hidden">
                <input class="form-control-plaintext" type="hidden" id="457" name="login" value="' .$anyuser->login. '">
              </div>
              <div class="col-md-3 col-6 mb-2 pe-1" >
                <input class="form-control" type="text" id="488" name="cash" value="' .$anyuser->cash. '" readonly>
              </div>
              <div class="col-md-3 col-6 mb-2 ps-1">
                <div class="input-group">
                  <input type="number" class="form-control" min="0" step="100" id="519" name="addedcash">
                  <button class="btn" type="submit">+</button>
                </div>
              </div>
            </div>
           </div>
        </form>
    ';
  };
?>

      <!-- Модальное окно очистки истории покупок -->
      <div class="modal fade" id="clearHistoryModal" tabindex="-1" aria-labelledby="clearHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="clearhistory.php" class="p-0" method="POST" id="clearHistoryForm" novalidate>
              <div class="modal-header">
                <h4 class="modal-title" id="clearHistoryModalLabel">Очистка истории покупок</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <select class="form-select" id="clear_history_user" name="clear_history_user">
                  <option id="clearHistorySelectHeader" selected>Выберите пользователя</option>
                <?php>
                  //Ищем записи пользователей, не являющихся админом
                  $userslist = R::find( 'users', 'isAdmin = :admin', [':admin' => false] );
                  //Выводим записи всех, не являющихся админом пользователей
                  foreach( $userslist as $anyuser ) {
                    echo '
                      <option value="' .$anyuser->login. '">' .$anyuser->login. '</option>
                    ';
                  };
                ?>
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                <button class="btn btn-warning" type="submit" name="clear_history">Очистить</button>
              </div>
            </form>
          </div>
        </div>
      </div>

</main>
</div>

<!--    Скрипты Bootstrap 5.0 с Popper-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>

<!-- Скрипты валидации -->
<script src="validation2.js"></script>

</body>