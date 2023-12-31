<?php 
if( empty( $db ) ) {
	http_response_code( 500 ) ;
	echo "Server error - empty DB" ;
	exit ;
}
if( empty( $_GET[ 'login' ] ) && !isset($_SESSION[ 'auth-user-id' ])) {
	http_response_code( 400 ) ;
	echo "Parameter 'login' required" ;
	exit ;
}
$login = $_GET[ 'login' ] ;
$password = $_GET[ 'password' ] ;


if (empty($login) && empty($password))
{
	unset($_SESSION[ 'auth-user-id' ]);
}
else
{	
	$sql = "SELECT * FROM users u WHERE u.`login` = ?" ;
	try {
		$prep = $db->prepare( $sql ) ;
		$prep->execute( [ $login ] ) ;
		$row = $prep->fetch() ;
	}
	catch( PDOException $ex ) {
		http_response_code( 500 ) ;
		echo "Server error - " . $ex->getMessage() ;
		exit ;
	}
	if( $row === false ) {
		http_response_code( 403 ) ;
		echo "Forbidden" ;
		exit ;
	}
	$dk = sha1( $row[ 'salt' ] . md5( $password ) ) ;
	if( $row[ 'pass_dk' ] == $dk ) {
		session_start() ;
		$_SESSION[ 'auth-user-id' ] = $row[ 'id' ] ;
		echo 'OK' ;
	}
	else {
		http_response_code( 403 ) ;
		echo "Forbidden2" ;
		exit ;
	}
}
/*
Д.З. Реалізувати
 - оновлення (перезавнтаження) сторінки після успішної авторизації
     (введення логіну і паролю в модальному вікні)
 - роботу кнопки "вихід" (logout) за аналогію з login:
     передати запит на /auth але іншим методом або з іншими параметрами
	 за його результатом оновити сторінку
*/
