<?php


function merge($thumb, $frame){

    $frame = imagecreatefrompng($frame);
    
    switch ( $_FILES['photo']['type'] ) {
        case 'image/jpeg':
            $thumb = imagecreatefromjpeg($thumb);
            break;
        case 'image/png':
            $thumb = imagecreatefrompng($thumb);
            break;
        case 'image/gif':
            $thumb = imagecreatefromgif($thumb);
            break;   
    }

    $width = imagesx( $frame );
    $height = imagesy( $frame );

    $img=imagecreatetruecolor( $width, $height );

    imagealphablending($img, true);

    $transparent = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
    imagefill( $img, 0, 0, $transparent );
    imagecopyresampled($img,$thumb,0,0,0,0, 200, 200, imagesx( $thumb ), imagesy( $thumb ) );
    imagecopyresampled($img,$frame,0,0,0,0, $width,$height,$width,$height);
    imagealphablending($img, false);
    imagesavealpha($img,true);


    return $img;

    imagedestroy($img);
    exit;
}

$file = $_FILES['photo']['name'];
$target = "img/".$file;
move_uploaded_file($_FILES['photo']['tmp_name'], $target);
$thumb = $target;
// $frame = '../Bast_Sales.png';
// $frame = '../H5.png';
$frame = '../'.$_POST['frame'].'.png';
// echo "img/".$_FILES['photo']['name'];

header("Content-type: getimagesize($target)");
imagepng( merge($thumb, $frame), NULL, 9 );

unlink( "img/".$_FILES['photo']['name'] );