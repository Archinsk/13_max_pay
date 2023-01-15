<?php //Страница авторизованного пользователя
  // Подключаем RedBeanPHP
  require 'rb.php';
  // Соединямся с БД
  R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' ); //Указываем адрес сервера, имя базы, логин и пароль пользователя (синтаксис RedBeanPHP)
  // Открываем сессию
  session_start();

  //Если на этой странице оказался незарегистрированный или разлогиненный пользователь, то переводим его на страницу неавторизованного пользователя
  if(!$_SESSION['logged_user']) {
    header('Location: maxscore.php');
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
          <a href="logout.php" class="btn btn-warning ms-3"><i class="bi bi-box-arrow-right"></i></a>
          <button type="button" class="btn btn-outline-light ms-3" data-bs-toggle="modal" data-bs-target="#purcheseHistoryModal"><i class="bi bi-info-circle"></i></button>
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
				  <input type="hidden" class="form-control" name="cost" value="10">
				  <button type="submit" class="btn btn-warning">Потратить 10 руб</button>
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
				  <input type="hidden" class="form-control" name="cost" value="250">
				  <button type="submit" class="btn btn-warning">Потратить 250 руб</button>
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
				  <input type="hidden" class="form-control" name="cost" value="300">
				  <button type="submit" class="btn btn-warning">Потратить 300 руб</button>
			    </form>
		      </div>
		    </div>
	      </div>

	      <div class="col mb-4">
		    <div class="card h-100 text-center">
		      <img src="images/5_rub.jpg" class="card-img-top" alt="...">
		      <div class="card-body">
			    <h5 class="card-title">Продукт №4</h5>
			    <p class="card-text">Здесь продается очень дорогая вещь</p>
			    <!-- Отправка значения покупки на сервер -->
			    <form id="purchase5" action="purchasing.php" method="POST">
				  <input type="hidden" class="form-control" name="cost" value="400">
				  <button type="submit" class="btn btn-warning">Потратить 400 руб</button>
			    </form>
		      </div>
		    </div>
	      </div>

	      <div class="col mb-4">
		    <div class="card h-100 text-center">
		      <img src="images/10_rub.jpg" class="card-img-top" alt="...">
		      <div class="card-body">
			    <h5 class="card-title">Продукт №5</h5>
			    <p class="card-text">Здесь продается очень дорогая вещь</p>
			    <!-- Отправка значения покупки на сервер -->
			    <form id="purchase10" action="purchasing.php" method="POST">
				  <input type="hidden" class="form-control" name="cost" value="500">
				  <button type="submit" class="btn btn-warning">Потратить 500 руб</button>
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

        <!-- Модальное окно истории покупок -->
        <div class="modal fade" id="purcheseHistoryModal" tabindex="-1" aria-labelledby="purcheseHistoryModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="purcheseHistoryModalLabel">История покупок</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <ul>
                  <?php>
                    $purchaseslist = R::find( 'purchases', 'login = :name', [':name' => $_SESSION['logged_user'] ] );
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
                        };
                      };
                  ?>
                </ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Скрипты Bootstrap 5.0 с Popper -->
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
              let notEnoughAlert = new bootstrap.Modal(document.getElementById("notEnoughAlert"));
              notEnoughAlert.show();
            </script>';
        };
        unset($_SESSION['is_enough']); //Сброс сведений о достаточности средств (для обновления страницы)
      };
    ?>

  </body>

</html>