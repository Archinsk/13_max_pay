<?php //Страница для неавторизованного пользователя

// Подключаем RedBeanPHP
require 'rb.php';
// Соединямся с БД
R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' ); //Указываем адрес сервера, имя базы, логин и пароль пользователя (синтаксис RedBeanPHP)
// Открываем сессию
session_start();

// В переменную data кладем все что передается от формы
$data = $_POST;
//Если нажата кнопка Войти
if(isset($data['do_login'])) {
  //Ищем пользователя по логину
  $user = R::findOne('users', 'login = ?', array($data['userLogin']));
    //Если логин существует,
    if ( $user ) { 
      //то проверяем пароль
      if ( password_verify($data['userPassword'], $user->password) ) {
        //если пароль совпадает, в сессию записываем логин и является ли он админом
        $userarray=$user->export();
        $_SESSION['logged_user'] = $userarray['login'];
		$_SESSION['is_admin'] = $userarray['isAdmin'];
		//Если пользователь авторизован,
        if(isset($_SESSION['logged_user']) ) {
		  //то проверяем, является ли он админом
		  if($_SESSION['is_admin'] == 1) {
		    //админа переводим на админскую страницу
            header('Location: maxscore_adm.php');
		  } else {
		    //не админа переводим на страницу авторизованного пользователя
            header('Location: maxscore_auth.php');
		  };
        };
      } else {
        $auth_error = 'Неверно введен пароль!';
      };
    } else {
      $auth_error = 'Пользователь не зарегистрирован!';
    };
  };
?>

<?php
  //Если нажималась кнопка "Зарегистрироваться"
  if ( isset($data['do_signup']) ) {
    //Создаем массив ошибок
    $reg_errors = array();
    //Если такой пользователь уже зарегистрирован
    if ( R::count('users', "login = ?", array($data['signupLogin'])) > 0) {
      $reg_errors[] = 'Логин уже занят';
    };
    //Если массив с ошибками пуст, то регистрируем пользователя
    if ( empty($reg_errors) ) {
      //Формируем кортеж с данными пользователя
      $user = R::dispense('users');
        $user->login = $data['signupLogin'];
        //Пароль вносим в хэшированном виде (работает толко с версии php 5.6)
        $user->password = password_hash($data['signupPassword'], PASSWORD_DEFAULT); 
      //Записываем в базу данных, в таблицу users, кортеж с данными пользователя        
      R::store($user);
	  //Записываем в сессию логин, по умолчанию делаем зарегистрированного пользователя "не админом" и отправляем на страницу зарегистрированного пользователя
      $_SESSION['logged_user'] = $data['signupLogin'];
	  $_SESSION['is_admin'] = 0;
      header('Location: maxscore_auth.php');
    };
  };
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
    <!-- Стили полей регистрации и авторизации с плавающими ярлыками -->
    <link rel="stylesheet" href="floating-labels.css">
    <title>Max Score</title> <!-- Название вкладки в браузере -->
</head>

<body>

<div class='container pt-3'>
  <header class="header">
	<div class="alert text-white text-center" role="alert">
		<h2>Платежи Max Pay</h2>
		<button type="button" class="btn btn-warning"data-bs-toggle="modal" data-bs-target="#authorizationModal"><i class="bi bi-box-arrow-in-right"></i></button>
	</div>
  </header>

  <main>
    <div id="stick" class="alert text-white text-center" role="alert">
      Получи 1000 рублей при регистрации!
    </div>
    <div class="row row-cols-1 row-cols-md-3">
    	  <div class="col mb-4">
    		<div class="card h-100 text-center">
    		  <img src="images/100_rub.jpg" class="card-img-top" alt="...">
    		  <div class="card-body">
    			<h5 class="card-title">Продукт №1</h5>
    		  </div>
    		</div>
    	  </div>
    	  <div class="col mb-4">
    		<div class="card h-100 text-center">
    		  <img src="images/500_rub.jpg" class="card-img-top" alt="...">
    		  <div class="card-body">
    			<h5 class="card-title">Продукт №2</h5>
    		  </div>
    		</div>
    	  </div>
    	  <div class="col mb-4">
    		<div class="card h-100 text-center">
    		  <img src="images/1000_rub.jpg" class="card-img-top" alt="...">
    		  <div class="card-body">
    			<h5 class="card-title">Продукт №3</h5>
    		  </div>
    		</div>
    	  </div>
    	</div>

    <!-- Модальное окно авторизации-->
    <div class="modal fade" id="authorizationModal" tabindex="-1" aria-labelledby="authorizationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
	      <form action="maxscore.php" class="p-0" method="POST" id="authorizationForm" novalidate> <!--Ссылка на себя-->
            <div class="modal-header">
              <h4 class="modal-title" id="authorizationModalLabel">Вход</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="form-label-group mb-3" id="logAuth">
                <input type="text" id="inputLogin" class="form-control" placeholder="Login" name="userLogin" value="<?php echo @$data['userLogin']; ?>" required autofocus>
                <label for="inputLogin" class="form-label" id="inputLoginLabel">Логин</label>
              </div>
              <div class="form-label-group mb-0" id="pasAuth">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="userPassword" value="<?php echo @$data['userPassword']; ?>" required>
                <label for="inputPassword" class="form-label" id="inputPasswordLabel">Пароль</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#registrationModal">Регистрация</button>
              <button class="btn btn-warning" type="submit" name="do_login">Войти</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Модальное окно регистрации-->
    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <form action="maxscore.php" class="p-0" method="POST" id="registrationForm" novalidate> <!--Ссылка на себя-->
            <div class="modal-header">
              <h4 class="modal-title" id="registrationModalLabel">Регистрация</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="form-label-group mb-3" id="logSign">
                <input type="text" id="signupLogin" class="form-control" placeholder="Login" name="signupLogin" value="<?php echo @$data['signupLogin']; ?>" id="signupLogin" required>
                <label for="signupLogin" class="form-label" id="signupLoginLabel">Логин</label>
              </div>
              <div class="form-label-group mb-3">
                <input type="password" id="signupPassword" class="form-control" placeholder="Password" name="signupPassword" value="<?php echo @$data['signupPassword']; ?>" id="signupPassword" required>
                <label for="signupPassword" class="form-label" id="signupPasswordLabel">Пароль</label>
              </div>
              <div class="form-label-group mb-0" id="passVerify">
                <input type="password" id="verifySignupPassword" class="form-control" placeholder="verifyPassword" name="verifySignupPassword" value="<?php echo @$data['verifySignupPassword']; ?>" id="verifySignupPassword" required>
                <label for="verifySignupPassword" class="form-label" id="verifySignupPasswordLabel">Пароль ещё раз</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
              <button class="btn btn-warning" type="submit" name="do_signup">Зарегистрироваться</button>
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

<!-- Скрипты валидации форм регистрации и авторизации-->
<script src="validation.js"></script>

  <?php
    //var_dump($auth_error);
    if (isset($auth_error) ) {
      echo '
        <!-- Скрипт уведомления об ошибках валидации при авторизации -->
        <script>
          var authorizationModal = new bootstrap.Modal(document.getElementById("authorizationModal"));
          authorizationModal.show();';
          if ($auth_error == 'Пользователь не зарегистрирован!') {
            echo '
            inputLogin.classList.add("error");
            let errorComment = createComment();
            let commentContent = document.createTextNode("Пользователь не зарегистрирован!");
            errorComment.appendChild(commentContent);
            logAuth.appendChild(errorComment);';
          } else if ($auth_error == 'Неверно введен пароль!') {
            echo '
            inputPassword.classList.add("error");
            let errorComment = createComment();
            let commentContent = document.createTextNode("Неверно введен пароль!");
            errorComment.appendChild(commentContent);
            pasAuth.appendChild(errorComment);';
          };
          echo '
        </script>';
      };

    if (! empty($reg_errors) ) {

        echo '
          <!-- Скрипт уведомления о выборе при регистрации уже занятого логина -->
          <script>
            var registrationModal = new bootstrap.Modal(document.getElementById("registrationModal"));
            registrationModal.show();
            signupLogin.classList.add("error");
            let errorComment = createComment();
            let commentContent = document.createTextNode("Данный логин уже занят!");
            errorComment.appendChild(commentContent);
            logSign.appendChild(errorComment);
          </script>';

    };
  ?>

</body>

</html>