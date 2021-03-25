<?php //Страница для авторизованного пользователя

  // Подключаем RedBeanPHP
  require 'rb.php';
  // Соединямся с БД
  R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' ); //Указываем адрес сервера, имя базы, логин и пароль пользователя (синтаксис RedBeanPHP)
  // Открываем сессию
  session_start();

  //Если на этой странице оказался незарегистрированный или разлогиненный пользователь, то переводим его на страницу неавторизованного пользователя
  if(!$_SESSION) {
    header('Location: maxscore.php');
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
      if ( password_verify($data['userPassword'], $user->password) ) {
        //если пароль совпадает, то нужно авторизовать пользователя
        $userarray=$user->export();
        $_SESSION['logged_user'] = $userarray['login'];
        echo '<div style="color:green;">' .$_SESSION['logged_user']. ', вы авторизованы!<hr>';
      } else {
        $errors[] = 'Неверно введен пароль!';
      };
    } else { //иначе (пользователь не найден)
      $errors[] = 'Пользователь с таким логином не найден!';
    };
  };
  //Вывод ошибок
if ( ! empty($errors) ) { //Если массив с ошибками не пуст
echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>'; // то выводим ошибки авторизации
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
  <div class='container pt-3'>

    <header class="header">
      <div class="alert text-white text-center" role="alert">
        <h2>Max Pay</h2>
        <a href="logout.php" class="btn btn-warning"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </header>
  
    <main>
      <div id="stick" class="alert text-white text-center" role="alert">

        <!-- Запрос суммы из базы данных -->
        <?php
		  //var_dump($_SESSION);
          $login = $_SESSION['logged_user'];
          $currentuser = R::findOne( 'users', ' login = :log ', [ ':log' => $login ] );
          echo 'На вашем счету <span class="badge bg-danger">' .$currentuser->cash. ' руб</span>';
        ?>

      </div>

	  <div class="row row-cols-1 row-cols-md-3">
	    <div class="col mb-4">
		  <div class="card h-100 text-center">
		    <img src="images/100_rub.jpg" class="card-img-top" alt="...">
		    <div class="card-body">
			  <h5 class="card-title">Продукт №1</h5>
			  <p class="card-text">Здесь можно купить что-то недорогое</p>
			  <!-- Отправка значения покупки на сервер -->
			  <form id="purchase100" action="purchasing.php" method="POST">
				<input type="hidden" class="form-control" name="cost" value="100">
				<button type="submit" class="btn btn-warning">Потратить 100 руб</button>
			  </form>
		    </div>
		  </div>
	    </div>
	    <div class="col mb-4">
		  <div class="card h-100 text-center">
		    <img src="images/500_rub.jpg" class="card-img-top" alt="...">
		    <div class="card-body">
			  <h5 class="card-title">Продукт №2</h5>
			  <p class="card-text">Здесь можно купить кое-что подороже</p>
			  <!-- Отправка значения покупки на сервер -->
			  <form id="purchase500" action="purchasing.php" method="POST">
				<input type="hidden" class="form-control" name="cost" value="500">
				<button type="submit" class="btn btn-warning">Потратить 500 руб</button>
			  </form>
		    </div>
		  </div>
	    </div>
	    <div class="col mb-4">
		  <div class="card h-100 text-center">
		    <img src="images/1000_rub.jpg" class="card-img-top" alt="...">
		    <div class="card-body">
			  <h5 class="card-title">Продукт №3</h5>
			  <p class="card-text">Здесь продается очень дорогая вещь</p>
			  <!-- Отправка значения покупки на сервер -->
			  <form id="purchase1000" action="purchasing.php" method="POST">
				<input type="hidden" class="form-control" name="cost" value="1000">
				<button type="submit" class="btn btn-warning">Потратить 1000 руб</button>
			  </form>
		    </div>
		  </div>
	    </div>
	  </div>

      <!-- Модальное окно уведомления о недостаточности средств -->
      <div class="modal fade" id="notEnoughAlert" tabindex="-1" aria-labelledby="notEnoughAlertLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="notEnoughAlertLabel">Недостаточно средств!</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>За пополнением обратитесь к аминистратору</p>
            </div>
          </div>
        </div>
      </div>

    </main>

  </div>

  <!--    Скрипты Bootstrap 5.0 с Popper-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
          crossorigin="anonymous"></script>

  <?php
    //var_dump($_SESSION);
    if(isset($_SESSION['is_enough']) ) {
      if ($_SESSION['is_enough'] == false) {
        echo '
          <!-- Скрипт уведомления о недостаточности средств -->
          <script>
            var notEnoughAlert = new bootstrap.Modal(document.getElementById("notEnoughAlert"));
            notEnoughAlert.show();
          </script>';
      };
      unset($_SESSION['is_enough']); //Сброс сведений о достаточности средств (для обновления страницы)
    };
  ?>

</body>

</html>