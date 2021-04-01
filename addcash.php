<?php //Пополнение админом счета пользователя
  //Подключаем RedBeanPHP
  require 'rb.php';
  R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' );
  // Открываем сессию
  session_start();

  //Из сессии передаем имя админа
  $refilladmin = $_SESSION['logged_user'];
  //Передаем все переменные из POST в переменную data
  $data = $_POST;
  $userlogin = $data['login'];
  $addedcash = $data['addedcash'];

  //Считываем текущую значение суммы на счету пользователя
  $user = R::findOne( 'users', ' login = :log ', [ ':log' => $userlogin ] );
  $cash = $user->cash;

  //Вычисляем значение после пополнения
  $newcash = $cash + $addedcash;
  //Фиксируем время пополнения
  $refillDate = time();

  //Обновляем сумму на счету пользователя с указанием админа-плательщика и даты
  $user->cash = $newcash;
  R::store($user);

  //Записываем начисление в историю транзакций
  $purchase = R::dispense('purchases');
    $purchase->login = $userlogin;
    $purchase->cost = $addedcash;
    $purchase->purchaseDate = $refillDate;
    $purchase->payerAdmin = $refilladmin;
  R::store($purchase);
	
  header('Location: maxscore_adm.php');
?>