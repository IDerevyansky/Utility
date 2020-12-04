<?php
@include('db.php');



if(isset($_POST["one"])){

	$post = strip_tags($_POST["task"]);	

	if(!empty($post)){

	$sql = "INSERT INTO tb_list_task (name_task) VALUES ('$post')";

		if ($conn->query($sql) === TRUE) {
   		//echo "<br>Успешно создана новая запись";
		} else {
   		//echo "<br>Ошибка: " . $sql . "<br>" . $conn->error;
		}

	}

	

	header("Location: ".$_SERVER['REQUEST_URI']);
}


//Выводим весь список заметок
$sql = "SELECT id, name_task FROM tb_list_task";
$result = $conn->query($sql);





if(isset($_GET["q"])){

	$id = $_GET["q"];

	$sql = "DELETE FROM tb_list_task WHERE id=$id";

	if ($conn->query($sql) === TRUE) {
   		//echo "Запись успешно удалена";
	} else {
   		//echo "Ошибка удаления записи: " . $conn->error;
	}

	header("Location: /");
}




?>