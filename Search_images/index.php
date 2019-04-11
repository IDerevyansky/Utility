<?php

function getImages($path){
	$fOpen = opendir($path);
	if($fOpen){

		$wiev = scandir($path);//смотрим содержимое

					$i=0;
					while ($i < count($wiev)) {
						$reserch = pathinfo($wiev[$i])['extension']; //определяем расширение
						$pattern =  $reserch == 'gif' || $reserch == 'jpg' || $reserch == 'png' || $reserch == 'tif'; //паттерн расширений

						
						if($pattern){
						
							$fullName = pathinfo($wiev[$i])['basename'];//полное название файла с расширением
							$sizeFile = lstat($path.'/'.$fullName)['size'];//вес изображения

							$img[$fullName] = $sizeFile;//массив имя=>вес

							if(5000000 < $sizeFile){ $limitSize[$fullName] = $sizeFile; } //установить лимит 5000000 байт
							
						}
						else{
							//echo 'non';
						}

						$i++;
					}

		print_r($limitSize);			
		//print_r($img);	//Array ( [download-1.jpg] => 4107 [download-2.jpg] => 9684 [download.jpg] => 3112 [foto_1.jpg] => 2939794 [foto_2.jpg] => 2939794 [foto_3.jpg] => 2939794 [foto_4.jpg] => 2939794 [giphy.gif] => 505160 [maxresdefault.jpg] => 235453 )	

	}else{
		echo'не вошел в директорию';
	}
}


$q = "foto";//директория
getImages($q);

?>