<?php //Добавление задания в список заданий
  // Подключаем RedBeanPHP и получаем доступ к БД
  require 'db.php';

  //Передаем все переменные из POST в переменную data
  $data = $_POST;
  $cost = $data['cost'];
  $name = $_SESSION['logged_user'];
  $purchaseDate = time();

  //Проверяем остаток средств
  $currentuser = R::findOne( 'users', ' login = :log ', [ ':log' => $name ] );
  $currentusercash = $currentuser->cash;

  //Проверка достаточности средств
  if ($currentusercash < $cost) {
    $_SESSION['is_enough'] = false;
    header('Location: maxscore_auth.php');
  } else {
    $_SESSION['is_enough'] = true;
    //Формируем кортеж с данными пользователя
    $purchase = R::dispense('purchases');
      $purchase->login = $name;
      $purchase->cost = $cost;
      $purchase->purchaseDate = $purchaseDate;
    R::store($purchase);
		
    //Обновляем сумму на счету пользователя
    $user = R::findOne( 'users', ' login = :log ', [ ':log' => $name ] );
    $oldcash = $user->cash;
    $user->cash = $oldcash - $cost;
    R::store($user);
	
    header('Location: maxscore_auth.php');
  }
?>