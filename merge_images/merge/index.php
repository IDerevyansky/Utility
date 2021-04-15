<?php
    move_uploaded_file($_FILES['photo']['tmp_name'], "img/".$_FILES['photo']['name']);

    $im = "img/".$_FILES['photo']['name'];
    $stamp = '../'.$_POST['frame'].'.png';

    $stamp = imagecreatefrompng($stamp);
    
    switch ( $_FILES['photo']['type'] ) {
        case 'image/jpeg':
            $im = imagecreatefromjpeg($im);
            break;
        case 'image/png':
            $im = imagecreatefrompng($im);
            break;
        case 'image/gif':
            $im = imagecreatefromgif($im);
            break;   
    }

// Установка полей для штампа и получение высоты/ширины штампа
$marge_right = 0;
$marge_bottom = 0;
$sx = imagesx($stamp);
$sy = imagesy($stamp);

// Копирование изображения штампа на фотографию с помощью смещения края
// и ширины фотографии для расчёта позиционирования штампа.
imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

// Вывод и освобождение памяти
header('Content-type: image/png');
header('Content-Disposition: attachment; filename="modify_'.date('Y-m-d')."_".$_FILES['photo']['name'].'"');

imagepng($im);
imagedestroy($im);

unlink( "img/".$_FILES['photo']['name'] );
    

?>



 