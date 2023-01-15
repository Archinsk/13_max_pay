<?php
//Очистка истории покупок пользователя

session_start();

//Подключаем RedBeanPHP
require 'rb.php';
R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' );

//Передаем из POST имя пользователя
$clearable_user = $_POST['clear_history_user'];

//Ищем все записи пользователя в таблице истории транзакций
$purchaseslist = R::find( 'purchases', 'login = :name', [':name' => $clearable_user] );

//Удаляем все записи из истории пользователя
R::trashAll( $purchaseslist );
	
header('Location: maxscore_adm.php');
?>