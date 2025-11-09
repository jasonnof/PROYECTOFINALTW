<?php


define('DB_HOST','localhost');
define('DB_NAME','EI1036_42_al426259');
define('DB_USER','root');
define('DB_PASSWORD','');


//Inicialozp el objeto PDO
$pdo = new PDO("mysql:host=" . DB_HOST . 
";dbname=" . DB_NAME,DB_USER,DB_PASSWORD);