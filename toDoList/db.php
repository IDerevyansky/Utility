<?php


$servername = "localhost";
$username = "user_test";
$password = "";


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("<br> Ошибка подключения: " . $conn->connect_error);
} 

// Создаем базу
$sql = "CREATE DATABASE DB_list_task";
if ($conn->query($sql) === TRUE) {
    //echo "<br>База данных <b>DB_list_task</b> успешно создана";
} else {
    //echo "<br>Ошибка создания базы данных: " . $conn->error;
}


$conn = new mysqli($servername, $username, $password, DB_list_task);
// Проверяем подключение
if ($conn->connect_error) {
    die("<br>Ошибка подключения к базе данных: " . $conn->connect_error);
} 


// Создаем таблицу
$sql = "CREATE TABLE tb_list_task (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
name_task VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    //echo "<br>Таблица <b>tb_list_task</b> успешно создана";
} else {
    //echo "<br>Ошибка создания таблицы: " . $conn->error;
}




//$conn->close();

?>